<?php
//Concrete Product
namespace App\Payments;

use App\Adapters\ToyyibPayPaymentAdapter;

class FPXPayment implements Payment
{
    public function pay(float $amount, array $data = []): array
    {
        $customer = array_merge([
            'bill_name' => $this->methodLabel(),
            'bill_description' => $data['payment_summary'] ?? $this->methodLabel(),
        ], $data['customer'] ?? []);

        $payload = (new ToyyibPayPaymentAdapter($this))->createCheckoutPayload($amount, $customer);

        return [
            'gateway' => 'ToyyibPay',
            'bill_code' => $payload['bill_code'],
            'redirect_url' => $payload['redirect_url'],
            'callback_data' => $payload,
        ];
    }

    public function driverCode(): string
    {
        return 'FPX';
    }

    public function methodLabel(): string
    {
        return 'FPX Payment';
    }

    public function paymentChannel(): int
    {
        return 0;
    }
}
