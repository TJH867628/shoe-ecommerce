<?php
//Concrete Product
namespace App\Payments;

use App\Adapters\StripeCheckoutAdapter;

class CardPayment implements Payment
{
    public function pay(float $amount, array $data = []): array
    {
        $session = (new StripeCheckoutAdapter($this))->createCheckoutSession(
            $amount,
            (int) $data['order_id'],
            (int) $data['payment_id'],
            $data['customer_email'] ?? null,
            $data['payment_summary'] ?? $this->methodLabel()
        );

        return [
            'gateway' => 'Stripe',
            'bill_code' => $session['id'],
            'redirect_url' => $session['url'],
            'callback_data' => $session,
        ];
    }

    public function driverCode(): string
    {
        return 'Card';
    }

    public function methodLabel(): string
    {
        return 'Card Payment';
    }

    public function paymentChannel(): int
    {
        return 1;
    }
}
