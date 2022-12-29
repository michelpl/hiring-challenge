<?php

namespace App\Services;
use App\Models\Boleto;
use App\Models\Charge;

interface IBoletoPaymentService 
{
    public function createPaymentMethod(Charge $charge): Boleto;
}