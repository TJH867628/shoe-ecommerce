<?php
//Abstract Creator
namespace App\Factory;

use App\Adapters\PaymentAdapter;
use App\Payments\Payment;

abstract class PaymentFactory
{
    abstract public function createPayment(): Payment;

    public function processPayment(float $amount, array $data = [])
    {
        $payment = $this->createPayment();

        return (new PaymentAdapter($payment))->createBill(array_merge($data, [
            'amount' => $amount,
        ]));
    }
}
