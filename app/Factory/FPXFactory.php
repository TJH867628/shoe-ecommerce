<?php
//Concrete Creator
namespace App\Factory;

use App\Payments\Payment;
use App\Payments\FPXPayment;

class FPXFactory extends PaymentFactory
{
    public function createPayment(): Payment
    {
        return new FPXPayment();
    }
}
