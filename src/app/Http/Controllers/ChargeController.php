<?php

namespace App\Http\Controllers;

use App\Factories\ChargeFactory;
use App\Http\Requests\UpdateChargeRequest;
use App\Models\Charge;
use App\Services\ChargeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChargeController extends Controller
{
    private ChargeService $chargeService;
    private Charge $charge;
    private ChargeFactory $chargeFactory;

    private array $chargeValidationRules = [
            'name' => 'required|max:255|string',
            'governmentId' => 'required|min:11|max:13|string',
            'email' => 'required|email',
            'debtAmount' => 'required|decimal:2|min:0.01|max:11',
            'debtDueDate' => 'required|date',
            'debtId' => 'required|integer|min:1|max:99999999'
    ];

    function __construct(
        ChargeFactory $chargeFactory, 
        ChargeService $chargeService,
    )
    {
        $this->chargeFactory = $chargeFactory;
        $this->chargeService = $chargeService;
    }

    public function list(): Response
    {
        try {
            (int) $rowsPerPage = env("CHARGE_LIST_ROWS_PER_PAGE");
            return $this->chargeService->paginatedChargeList($rowsPerPage);
        } catch(\Exception $e) {
            return $this->handleResponseException($e);
        }
    }

    public function store(Request $request): Response
    {
        try {
            $request->validate($this->chargeValidationRules);
            $charge = $this->chargeService->createCharge(
                $this->chargeFactory->createFromRequestData($request)
            );

            return response($charge, 201);

        } catch (\Exception $e) {
            return $this->handleResponseException($e);
        }
    }

    public function sendChargeToCustomer(): Response
    {
        try {
            $this->chargeService->sendChargeToCustomer();

            return response('', 200);

        } catch (\Exception $e) {
            return $this->handleResponseException($e);
        }
    }

    private function handleResponseException(\Exception $exception): Response
    {
        $message = $exception->getMessage();
        $code = $exception->getCode();

        if (isset($exception->validator)) {
            $validator = $exception->validator;
            $message = [
                'Invalid request data',
                $validator->messages()
            ];

            $code = 422;
        }

        return response($message, $code);
    }
}
