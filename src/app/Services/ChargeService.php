<?php

namespace App\Services;

use App\Models\Charge;

class ChargeService 
{
    public function paginatedChargeList(Int $rowsPerPage = 1) 
    {
        return Charge::paginate($rowsPerPage);
    }
}