<?php

namespace App\Adapters;

use App\Contracts\ToyyibPayPaymentMethod;
use App\Services\ToyyibPayConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use App\Payments\Payment as PaymentMethod;

class ToyyibPayPaymentAdapter
{
    public function __construct(
        private ?PaymentMethod $paymentMethod = null
    ) {
    }

    public function createCheckoutPayload(float $amount, array $customer = []): array
    {
        $toyyibPayConfig = ToyyibPayConfig::getInstance();
        $secretKey = $toyyibPayConfig->getSecretKey();
        $categoryCode = $toyyibPayConfig->getCategoryCode();
        $baseUrl = $toyyibPayConfig->getBaseUrl();
        $returnUrl = $customer['return_url'] ?? route('toyyibpay.return');
        $callbackUrl = $customer['callback_url'] ?? route('toyyibpay.callback');

        if (! $secretKey || ! $categoryCode) {
            throw new RuntimeException('ToyyibPay credentials are not configured.');
        }

        $reference = $customer['reference'] ?? (string) Str::uuid();
        $billAmountInCents = (int) round($amount * 100);

        $payload = [
            'userSecretKey' => $secretKey,
            'categoryCode' => $categoryCode,
            'billName' => $customer['bill_name'] ?? $this->paymentMethod->methodLabel(),
            'billDescription' => $customer['bill_description'] ?? 'ToyyibPay checkout via Laravel adapter',
            'billPriceSetting' => 0,
            'billPayorInfo' => 1,
            'billAmount' => $billAmountInCents,
            'billReturnUrl' => $returnUrl,
            'billCallbackUrl' => $callbackUrl,
            'billExternalReferenceNo' => $reference,
            'billTo' => $customer['name'] ?? 'Customer',
            'billEmail' => $customer['email'] ?? 'customer@example.com',
            'billPhone' => $customer['phone'] ?? '0000000000',
            'billSplitPayment' => 0,
            'billPaymentChannel' => $this->paymentMethod->paymentChannel(),
        ];

        $response = Http::asForm()->post(rtrim($baseUrl, '/') . '/index.php/api/createBill', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('ToyyibPay createBill request failed: ' . $response->body());
        }

        $responseData = $response->json();
        $billCode = $responseData['0']['BillCode'] ?? null;

        if (! $billCode) {
            throw new RuntimeException('ToyyibPay did not return a bill code. Response: ' . $response->body());
        }

        $redirectUrl = rtrim($baseUrl, '/') . '/' . $billCode;

        return [
            'gateway' => 'ToyyibPay',
            'payment_method' => $this->paymentMethod->driverCode(),
            'payment_label' => $this->paymentMethod->methodLabel(),
            'amount' => number_format($amount, 2, '.', ''),
            'bill_amount_cents' => $billAmountInCents,
            'reference' => $reference,
            'bill_code' => $billCode,
            'redirect_url' => $redirectUrl,
            'customer' => [
                'name' => $customer['name'] ?? null,
                'email' => $customer['email'] ?? null,
                'phone' => $customer['phone'] ?? null,
            ],
            'credentials' => [
                'userSecretKey' => $secretKey,
                'categoryCode' => $categoryCode,
            ],
            'toyyibpay_base_url' => $baseUrl,
            'summary' => $this->paymentMethod->methodLabel() . ' checkout created successfully.',
            'api_response' => $responseData,
        ];
    }
}
