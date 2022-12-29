<?php

namespace App\Factories;
use App\Models\Charge;
use Illuminate\Http\Request;
use Throwable;

class ChargeFactory implements FactoryInterface
{
    private Charge $charge;
    function __construct(Charge $charge)
    {
        $this->charge = $charge;
    }

    public function createFromRequestData(Request $requestData): Charge
    {
        $this->charge->debt_id = $requestData->debtId;
        $this->charge->name = $requestData->name;
        $this->charge->government_id = preg_replace('/[^0-9]/', '', $requestData->governmentId);
        $this->charge->email = $requestData->email;
        $this->charge->debt_amount = $requestData->debtAmount;
        $this->charge->debt_due_date = $requestData->debtDueDate;

        return $this->charge;
    }

    public function createFromArray(array $csvRow): Charge
        {
        $charge = new Charge;
        $charge->debt_id = (int) $csvRow['debtId'];
        $charge->name = $csvRow['name'];
        $charge->government_id = preg_replace('/[^0-9]/', '', $csvRow['governmentId']);
        $charge->email = $csvRow['email'];
        $charge->debt_amount = $csvRow['debtAmount'];
        $charge->debt_due_date = $csvRow['debtDueDate'];

        return $charge;
    }
}