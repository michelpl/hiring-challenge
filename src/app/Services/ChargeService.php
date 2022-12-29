<?php

namespace App\Services;

use App\Models\Charge;
use Illuminate\Support\Facades\DB;
use Throwable;

class ChargeService 
{
    private BoletoService $paymentService;

    function __construct(BoletoService $boletoService)
    {
        $this->paymentService = $boletoService;
    }

    const CHARGES_TABLE = 'charges';
    public function paginatedChargeList(Int $rowsPerPage = 1) 
    {
        return Charge::paginate($rowsPerPage);
    }

    public function createCharge(Charge $charge) 
    {
        try {
            DB::beginTransaction();

            $createdCharge = $charge->firstOrCreate(
                ['debt_id' => $charge->debt_id],
                [
                    'name' => $charge->name,
                    'government_id' => $charge->government_id,
                    'email' => $charge->email,
                    'debt_amount' => $charge->debt_amount,
                    'debt_due_date' => $charge->debt_due_date,
                    'debt_id' => $charge->debt_id,
                    'paid_at' => $charge->paid_at,
                    'paid_amount' => $charge->paid_amount,
                    'paid_by' => $charge->paid_by
                ]
            );
            $this->paymentService->createChargePaymentMethod($createdCharge);

            DB::commit();
            
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function sendChargeToCustomer(): Throwable
    {
        //Enviar e-mail
    }
}