<?php

namespace App\Services;

use App\Models\Charge;

class ChargeService 
{
    public function paginatedChargeList(Int $rowsPerPage = 1) 
    {
        return Charge::paginate($rowsPerPage);
    }

    public function createCharge(Charge $charge): Charge {
        $charge->saveOrFail();
        return $charge;
    }

    public function createChargeFromList() {

    }
}