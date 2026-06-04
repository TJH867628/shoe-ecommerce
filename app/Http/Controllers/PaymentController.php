<?php

namespace App\Http\Controllers;

use App\Adapters\ToyyibPayPaymentAdapter;
use App\Factory\FPXFactory;
use App\Factory\CardFactory;
use App\Factory\PaymentFactory;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
            'customer_phone' => [
                'required',
                'string',
                'max:30',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (!$this->isValidMalaysiaPhoneNumber((string) $value)) {
                        $fail('Please enter a valid Malaysia phone number, for example 0123456789 or +60123456789.');
                    }
                },
            ],
        ]);

        $phone = $this->normalizeMalaysiaPhoneNumber($validated['customer_phone']);

        $cart = Cart::where('user_id', auth()->id())
            ->with('items.variation.shoe')
            ->first();

        $order = Order::create([
            'user_id' => auth()->id(),
            'voucher_id' => null,
            'total_amount' => $validated['amount'],
            'status' => 'pending',
        ]);

        foreach ($cart->items as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'shoe_variation_id' => $cartItem->shoe_variation_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->variation->shoe->shoe_price,
            ]);
        }

        $paymentRecord = Payment::create([
            'order_id' => $order->id,
            'payment_amount' => $validated['amount'],
            'payment_status' => 'pending',
            'payment_method' => $validated['payment_type'],
        ]);

        $factory = $this->resolveFactory($validated['payment_type']);
        $payment = $factory->createPayment();
        $adapter = new ToyyibPayPaymentAdapter($payment);

        $payload = $adapter->createCheckoutPayload((float) $validated['amount'], [
            'name' => $validated['customer_name'] ?? $request->user()?->name,
            'email' => $validated['customer_email'] ?? $request->user()?->email,
            'phone' => $phone,
            'reference' => 'CHK-' . now()->format('YmdHis'),
        ]);

        $paymentRecord->update([
            'bill_code' => $payload['bill_code'],
        ]);

        return redirect()
            ->away($payload['redirect_url'])
            ->with('checkout_payload', $payload);
    }



    private function updateToyyibPayPayment(Request $request): bool
    {
        $billCode = $request->input('billcode')
            ?? $request->input('BillCode')
            ?? $request->input('bill_code');

        $statusId = (string) $request->input('status_id');

        if (!$billCode) {
            return false;
        }

        $payment = Payment::with('order')
            ->where('bill_code', $billCode)
            ->first();

        if (!$payment) {
            return false;
        }

        if ($statusId === '1') {
            $alreadySuccessful = $payment->payment_status === 'success';

            $payment->update([
                'payment_status' => 'success',
                'transaction_id' => $request->input('transaction_id'),
                'callback_data' => $request->all(),
                'paid_at' => $payment->paid_at ?? now(),
            ]);

            $payment->order->update([
                'status' => 'paid',
            ]);

            if (!$alreadySuccessful) {
                Cart::where('user_id', $payment->order->user_id)
                    ->first()
                        ?->items()
                    ->delete();
            }

            return true;
        }

        if ($statusId === '3') {
            $payment->update([
                'payment_status' => 'failed',
                'callback_data' => $request->all(),
            ]);

            $payment->order->update([
                'status' => 'cancelled',
            ]);

            return true;
        }

        if ($payment->payment_status === 'success') {
            return true;
        }

        $payment->update([
            'payment_status' => 'pending',
            'callback_data' => $request->all(),
        ]);

        return true;
    }

    public function toyyibpayReturn(Request $request)
    {
        $statusId = (string) $request->query('status_id');
        $this->updateToyyibPayPayment($request);

        $message = match ($statusId) {
            '1' => 'ToyyibPay payment completed successfully.',
            '2' => 'ToyyibPay payment is still pending.',
            '3' => 'ToyyibPay payment failed.',
            default => 'Returned from ToyyibPay without a payment status.',
        };

        $flashKey = $statusId === '3' ? 'failed' : 'success';

        return redirect()
            ->route('user.payment-success')
            ->with($flashKey, $message)
            ->with('checkout_result', [
                'status_id' => $statusId,
                'billcode' => $request->query('billcode'),
                'order_id' => $request->query('order_id'),
            ]);
    }


    public function toyyibpayCallback(Request $request): JsonResponse
    {
        $updated = $this->updateToyyibPayPayment($request);

        if ($updated) {
            return response()->json(['message' => 'Payment record updated successfully']);
        }

        return response()->json(['message' => 'Payment record not found or invalid data'], 404);
    }

    public function paymentSuccess(Request $request)
    {
        $result = session('checkout_result');

        return view('user.payment-success', [
            'result' => $result,
            'success' => session('success'),
            'failed' => session('failed'),
        ]);
    }

    private function resolveFactory(string $paymentType): PaymentFactory
    {
        return match ($paymentType) {
            'FPX' => new FPXFactory(),
            'Card' => new CardFactory(),
            default => throw new InvalidArgumentException('Invalid Payment Type'),
        };
    }

    private function normalizeMalaysiaPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[\s\-.()]/', '', $phone) ?? '';

        if (str_starts_with($phone, '+60')) {
            return '0' . substr($phone, 3);
        }

        if (str_starts_with($phone, '60')) {
            return '0' . substr($phone, 2);
        }

        return $phone;
    }

    private function isValidMalaysiaPhoneNumber(string $phone): bool
    {
        $normalized = $this->normalizeMalaysiaPhoneNumber($phone);

        return preg_match('/^0(?:1[0-46-9][0-9]{7,8}|[3-9][0-9]{7,8})$/', $normalized) === 1;
    }
}
