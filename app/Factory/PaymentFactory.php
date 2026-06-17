<?php
//Abstract Creator
namespace App\Factory;

use App\Payments\Payment;

abstract class PaymentFactory
{
    abstract public function createPayment(): Payment;

    public function processPayment(float $amount, array $data = [])
    {
        $payment = $this->createPayment();

        return $payment->pay($amount, $data);
    }
}
