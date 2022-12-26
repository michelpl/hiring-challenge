<?php

namespace App\Http\Controllers;

use App\Factories\CsvDataFactory;
use App\Models\CsvData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CsvDataController extends Controller
{
    private CsvData $csvData;
    private CsvDataFactory $csvDataFactory;

    function __construct(CsvDataFactory $csvDataFactory, CsvData $csvData)
    {
        $this->csvData = $csvData;
        $this->csvDataFactory = $csvDataFactory;
    }

    public function store(Request $request)
    {
        /**
         * @todo text MOVE VALIDATION TO OTHER PLACE
         */
        if ($request->file('csv_file')->getClientOriginalExtension() != env('DATA_FILE_EXTENSION')) {
            return 
                response(
                    'Not supported file extension: ' .
                    $request->file('csv_file')->getClientOriginalExtension() .
                    ' | Send the correct file extension: '.
                    env('DATA_FILE_EXTENSION'),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
        }

        if ($request->file('csv_file')->getSize() > env('MAX_DATA_FILE_SIZE_IN_BYTES')) {
            return 
                response(
                    env('DATA_FILE_EXTENSION') . 
                        " file size should be shorter than" . 
                        env('MAX_DATA_FILE_SIZE_IN_BYTES') . 
                        ' bytes', 
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
        }

        try {
            $csvData = $this->csvDataFactory->createFromRequestData($request);
            $csvData->saveOrFail();
        } catch(\Exception $e) {
            return response("Something went wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
