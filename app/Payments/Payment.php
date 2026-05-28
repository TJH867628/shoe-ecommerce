<?php
//Product Interface
namespace App\Payments;

use App\Contracts\ToyyibPayPaymentMethod;

interface Payment extends ToyyibPayPaymentMethod
{
    public function pay(float $amount);
}