<?php

namespace App\Services;

use App\Factories\CsvDataFactory;
use App\Models\Charge;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use TheSeer\Tokenizer\Exception;
use Throwable;
                  
class CsvDataService 
{
    private CsvDataFactory $csvDataFactory;

    function __construct(CsvDataFactory $csvDataFactory)
    {
        $this->csvDataFactory = $csvDataFactory;
    }
    public function createFromRequestData(Request $request): bool | Throwable
    {
        $csvData = $this->csvDataFactory->createFromRequestData($request);
        return $csvData->saveOrFail();
    }
    public function createChargeFromList() {

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