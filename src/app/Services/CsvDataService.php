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
use TheSeer\Tokenizer\Exception;
use Throwable;
use App\Factories\ChargeFactory;
                  
class CsvDataService 
{
    const CHARGES_TABLE = 'charges';
    const CSV_FILE_TABLE = 'csv_data';
    private CsvDataFactory $csvDataFactory;
    private ChargeFactory $chargeFactory;
    private CsvData $csvData;
    private Charge $charge;

    function __construct(
        CsvDataFactory $csvDataFactory, 
        ChargeFactory $chargeFactory,
        CsvData $csvData, 
        Charge $charge
    )
    {
        $this->csvDataFactory = $csvDataFactory;
        $this->chargeFactory = $chargeFactory;
        $this->csvData = $csvData;
        $this->charge = $charge;
    }
    
    public function createFromRequestData(Request $request): bool | Throwable
    {
        $csvData = $this->csvDataFactory->createFromRequestData($request);
        return $csvData->saveOrFail();
    }
    
    public function createChargeFromDatabase() 
    {
        try {
            DB::beginTransaction();

            foreach ($this->getCsvList() as $csvFile) {
                if ( $this->createCharges($csvFile)) {
                    $this->updateCsvStatus($csvFile->id);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function createCharges(stdClass $csvFile): bool
    {
        if (empty($csvFile->csv_header) || empty($csvFile->csv_data)) {
            throw new Exception("Not formatted CSV file", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $header = json_decode($csvFile->csv_header);
        $data = json_decode($csvFile->csv_data);
        
        foreach ($data as $newCharge) {
            $newData[] = $this->chargeFactory->createFromCsvRow(array_combine($header, $newCharge));
        }

        return DB::table(self::CHARGES_TABLE)->insert($newData);
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

    public function validateHttpRequest(Request $request): bool | Throwable{
        if ($request->file('csv_file')->getClientOriginalExtension() != env('DATA_FILE_EXTENSION')) {
            throw new Exception(
                'Not supported file extension: ' .
                $request->file('csv_file')->getClientOriginalExtension() .
                ' | Send the correct file extension: '.
                env('DATA_FILE_EXTENSION'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($request->file('csv_file')->getSize() > env('MAX_DATA_FILE_SIZE_IN_BYTES')) {
            throw new Exception(
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