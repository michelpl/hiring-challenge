<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebhookRequest;
use App\Repositories\LogRepository;
use App\Services\ChargeService;
use Illuminate\Http\Request;
use Exception;
   
class WebhookController extends Controller
{
    private array $chargeValidationRules = [
        'debtId' => 'required|integer|min:1|max:99999999',
        'paidAt' => 'required|date_format:Y-m-d H:i:s',
        'paidAmount' => 'required|decimal:2|min:1|max:19',
        'paidBy' => 'required|min:2|max:255|string',
    ];

    private ChargeService $chargeService;
    private WebhookRequest $webhookRequest;

    public function __construct(ChargeService $chargeService, WebhookRequest $webhookRequest){
        $this->chargeService = $chargeService;
        $this->webhookRequest = $webhookRequest;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request){
        {
            try {
                
                $request->validate($this->chargeValidationRules);

                $this->webhookRequest
                    ->setDebtId($request->debtId)
                    ->setPaidAmount($request->paidAmount)
                    ->setPaidAt($request->paidAt)
                    ->setPaidBy($request->paidBy);

                return $this->chargeService->payCharge($this->webhookRequest);
    
    
            } catch (Exception $e) {
                return $this->handleExceptionResponse($e);
            }
        }
    }

    private function handleExceptionResponse(Exception $e)
    {
        $message = $e->getMessage();
        $code = $e->getCode();

        LogRepository::warning('Could not pay the charge by webhook:' . $e->getMessage());

        if (isset($e->validator)) {
            $validator = $e->validator;
            $message = [
                'Invalid request data',
                $validator->messages()
            ];

            $code = 422;
        }

        return response($message, 400);
    }
}
