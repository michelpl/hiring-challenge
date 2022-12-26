<?php

namespace App\Http\Controllers;

use App\Factories\CsvDataFactory;
use App\Models\CsvData;
use App\Services\CsvDataService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CsvDataController extends Controller
{
    private CsvData $csvData;
    private CsvDataFactory $csvDataFactory;
    private CsvDataService $csvDataService;

    function __construct(CsvDataFactory $csvDataFactory, CsvData $csvData, CsvDataService $csvDataService)
    {
        $this->csvData = $csvData;
        $this->csvDataFactory = $csvDataFactory;
        $this->csvDataService = $csvDataService;
    }

    public function store(Request $request)
    {
        try {
            $this->csvDataService->validateHttpRequest($request);
            $this->csvDataService->createFromRequestData($request);
            return response(Response::HTTP_CREATED);
        } catch(\Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }

    public function createChargeFromCSVDatabase(): Response {
        try {
            $this->csvDataService->createChargeFromList();
            return response(Response::HTTP_OK);
        } catch(\Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }
}
