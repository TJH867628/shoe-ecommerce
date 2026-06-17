<?php

namespace App\Adapters;

use App\Payments\Payment as PaymentMethod;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class StripeCheckoutAdapter
{
    private string $baseUrl = 'https://api.stripe.com/v1';

    public function __construct(
        private ?PaymentMethod $paymentMethod = null
    ) {
    }

    public function createCheckoutSession(
        float $amount,
        int $orderId,
        int $paymentId,
        ?string $customerEmail = null,
        ?string $paymentSummary = null
    ): array {
        $secretKey = config('services.stripe.secret');

        if (! $secretKey) {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        $payload = [
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'myr',
                        'product_data' => [
                            'name' => ($this->paymentMethod?->methodLabel() ?? 'Card Payment') . " - Shoe order #{$orderId}",
                        ],
                        'unit_amount' => (int) round($amount * 100),
                    ],
                    'quantity' => 1,
                ],
            ],
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel', ['order_id' => $orderId]),
            'metadata' => [
                'order_id' => (string) $orderId,
                'payment_id' => (string) $paymentId,
                'payment_method' => $this->paymentMethod?->driverCode() ?? 'Card',
                'payment_summary' => $paymentSummary ?? $this->paymentMethod?->methodLabel() ?? 'Card Payment',
            ],
        ];

        if ($customerEmail) {
            $payload['customer_email'] = $customerEmail;
        }

        $response = Http::withToken($secretKey)
            ->asForm()
            ->post($this->baseUrl . '/checkout/sessions', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Stripe checkout request failed: ' . $response->body());
        }

        $session = $response->json();

        if (empty($session['id']) || empty($session['url'])) {
            throw new RuntimeException('Stripe did not return a checkout session URL.');
        }

        return $session;
    }

    public function retrieveCheckoutSession(string $sessionId): array
    {
        $secretKey = config('services.stripe.secret');

        if (! $secretKey) {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        $response = Http::withToken($secretKey)
            ->get($this->baseUrl . '/checkout/sessions/' . urlencode($sessionId));

        if (! $response->successful()) {
            throw new RuntimeException('Unable to verify the Stripe checkout session.');
        }

        return $response->json();
    }
}
