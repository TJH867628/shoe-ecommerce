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
        $amount = (float) $data['amount'];
        $payload = [
            'amount' => $amount,
            'order_id' => (int) $data['order_id'],
            'payment_id' => (int) $data['payment_id'],
            'customer_email' => $data['customer_email'] ?? null,
            'customer' => $data['customer'] ?? [],
            'payment_summary' => $this->payment->methodLabel() . ' processed successfully for RM ' . number_format($amount, 2) . '.',
        ];

        return $this->payment->pay($amount, $payload);
    }
}
