<?php

namespace App\Adapters;

use App\Payments\Payment;
use InvalidArgumentException;

class PaymentAdapter
{
    public function __construct(
        private Payment $payment
    ) {
    }

    public function createBill(array $data): array
    {
        $amount = (float) $data['amount'];
        $paymentSummary = $this->payment->pay($amount);

        if ($this->payment->driverCode() === 'Card') {
            $session = (new StripeCheckoutAdapter($this->payment))->createCheckoutSession(
                $amount,
                (int) $data['order_id'],
                (int) $data['payment_id'],
                $data['customer_email'] ?? null,
                $paymentSummary
            );

            return [
                'gateway' => 'Stripe',
                'bill_code' => $session['id'],
                'redirect_url' => $session['url'],
                'callback_data' => $session,
            ];
        }

        if ($this->payment->driverCode() === 'FPX') {
            $customer = array_merge([
                'bill_name' => $this->payment->methodLabel(),
                'bill_description' => $paymentSummary,
            ], $data['customer'] ?? []);

            $payload = (new ToyyibPayPaymentAdapter($this->payment))->createCheckoutPayload(
                $amount,
                $customer
            );

            return [
                'gateway' => 'ToyyibPay',
                'bill_code' => $payload['bill_code'],
                'redirect_url' => $payload['redirect_url'],
                'callback_data' => $payload,
            ];
        }

        throw new InvalidArgumentException('Unsupported payment adapter type.');
    }
}
