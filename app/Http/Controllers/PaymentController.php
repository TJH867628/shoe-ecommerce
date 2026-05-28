<?php

namespace App\Http\Controllers;

use App\Adapters\ToyyibPayPaymentAdapter;
use App\Factory\FPXFactory;
use App\Factory\CardFactory;
use App\Factory\PaymentFactory;
use Illuminate\Http\Request;
use InvalidArgumentException;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
            'payment_type' => ['required', 'in:FPX,Card'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
        ]);

        $factory = $this->resolveFactory($validated['payment_type']);
        $payment = $factory->createPayment();
        $adapter = new ToyyibPayPaymentAdapter($payment);

        $payload = $adapter->createCheckoutPayload((float) $validated['amount'], [
            'name' => $validated['customer_name'] ?? $request->user()?->name,
            'email' => $validated['customer_email'] ?? $request->user()?->email,
            'phone' => $validated['customer_phone'] ?? null,
            'reference' => 'CHK-' . now()->format('YmdHis'),
        ]);

        return redirect()
            ->away($payload['redirect_url'])
            ->with('checkout_payload', $payload);
    }

    private function resolveFactory(string $paymentType): PaymentFactory
    {
        return match ($paymentType) {
            'FPX' => new FPXFactory(),
            'Card' => new CardFactory(),
            default => throw new InvalidArgumentException('Invalid Payment Type'),
        };
    }
}