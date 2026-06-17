<?php

namespace App\Adapters;

use App\Payments\Payment;

class PaymentAdapter
{
    public function __construct(
        private Payment $payment
    ) {
    }

    public function createBill(array $data): array
    {
        return $this->payment->pay((float) $data['amount'], $data);
    }
}
