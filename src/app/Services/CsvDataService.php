<?php

namespace App\Services;

use App\Factories\CsvDataFactory;
use App\Models\Charge;
use App\Models\CsvData;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use App\Factories\ChargeFactory;
use App\Services\BoletoService;
use App\Services\ChargeService;
                  
class CsvDataService 
{
    const CSV_FILE_TABLE = 'csv_data';
    private CsvDataFactory $csvDataFactory;
    private ChargeFactory $chargeFactory;
    private CsvData $csvData;
    private Charge $charge;
    private BoletoService $boletoService;
    private ChargeService $chargeService;

    function __construct(
        CsvDataFactory $csvDataFactory, 
        ChargeFactory $chargeFactory,
        CsvData $csvData, 
        Charge $charge,
        BoletoService $boletoService,
        ChargeService $chargeService
    )
    {
        $this->csvDataFactory = $csvDataFactory;
        $this->chargeFactory = $chargeFactory;
        $this->csvData = $csvData;
        $this->charge = $charge;
        $this->boletoService = $boletoService;
        $this->chargeService = $chargeService;
    }
    
    public function createFromRequestData(Request $request)
    {
        try {
            $csvData = $this->csvDataFactory->createFromRequestData($request);
            DB::beginTransaction();
            $csvData->upsert([
                'csv_file_hash' => $csvData->csv_file_hash,
                'csv_filename' => $csvData->csv_filename,
                'csv_header' => $csvData->csv_header,
                'csv_data' => $csvData->csv_data
            ], 'csv_file_hash');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
    
    public function createChargeFromDatabase() 
    {
        try {
            foreach ($this->getCsvList() as $csvFile) {
                return $this->createCharges($csvFile);
                if (!$this->createCharges($csvFile)) {
                    return false;
                }
                $this->updateCsvStatus($csvFile->id);
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    private function createCharges(stdClass $csvFile)
    {
        
        if (empty($csvFile->csv_header) || empty($csvFile->csv_data)) {
            throw new \Exception("Not formatted CSV file", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $data = json_decode($csvFile->csv_data);
        $header = json_decode($csvFile->csv_header);

        if (empty($data)) {
            return false;
        }
        
        foreach ($data as $chargeArray) {
            $charge = $this->chargeFactory->createFromArray(array_combine($header, $chargeArray));
            if(!$this->chargeService->createCharge($charge)) {
                return false;
            }
        }

        return true;
    }

    private function updateCsvStatus(int $csvFileId)
    {
        DB::table(self::CSV_FILE_TABLE)
        ->where('id', $csvFileId)
        ->update(['status' => 'migrated']);
    }

    private function getCsvList(): Collection {
        return DB::table(self::CSV_FILE_TABLE)
        ->oldest()
        ->where('status', 'pending')
        ->limit(env('CSV_PROCESSING_LIMIT', 1))
        ->get();
    }

    public function validateHttpRequest(Request $request): bool
    {
        if (empty($request->file('csv_file'))) {
            throw new \Exception('Empty csv_file field', Response::HTTP_BAD_REQUEST);
        }

        if (
            $request->file('csv_file')->getClientOriginalExtension() != env('DATA_FILE_EXTENSION')
        ) {
            throw new \Exception(
                'Not supported file extension: ' .
                $request->file('csv_file')->getClientOriginalExtension() .
                ' | Send the correct file extension: '.
                env('DATA_FILE_EXTENSION'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($request->file('csv_file')->getSize() > env('MAX_DATA_FILE_SIZE_IN_BYTES')) {
            throw new \Exception(
                    env('DATA_FILE_EXTENSION') . 
                        " file size should be shorter than" . 
                        env('MAX_DATA_FILE_SIZE_IN_BYTES') . 
                        ' bytes', 
                    Response::HTTP_UNPROCESSABLE_ENTITY
            );     
        }

        return true;
    }
}