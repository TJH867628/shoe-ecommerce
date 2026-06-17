<?php

namespace App\Http\Controllers;

use App\Adapters\PaymentAdapter;
use App\Adapters\StripeCheckoutAdapter;
use App\Factory\CardFactory;
use App\Factory\FPXFactory;
use App\Factory\PaymentFactory;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use RuntimeException;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function paymentPage(Request $request)
    {
        $user = $request->user();

        return view('user.payment', [
            'amount' => $request->query('amount', 500),
            'subtotal' => $request->query('subtotal', 500),
            'discountAmount' => $request->query('discount_amount', 0),
            'shipping' => $request->query('shipping', 0),
            'shippingMethod' => $request->query('shipping_method', 'standard'),
            'paymentType' => $request->query('payment_type', 'FPX'),
            'customerName' => $request->query('customer_name', $user?->name),
            'customerEmail' => $request->query('customer_email', $user?->email),
            'customerPhone' => $request->query('customer_phone', $user?->phone ?? ''),
        ]);
    }

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

        $cart = Cart::where('user_id', Auth::id())
            ->with('items.variation.shoe')
            ->first();

        $order = Order::create([
            'user_id' => Auth::id(),
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

        $factory = $this->resolveFactory($validated['payment_type']);
        $payment = $factory->createPayment();
        $paymentMethod = $payment->driverCode();

        $paymentRecord = Payment::create([
            'order_id' => $order->id,
            'payment_amount' => $validated['amount'],
            'payment_status' => 'pending',
            'payment_method' => $paymentMethod,
        ]);

        $adapter = new PaymentAdapter($payment);

        try {
            $payload = $adapter->createBill([
                'amount' => (float) $validated['amount'],
                'order_id' => $order->id,
                'payment_id' => $paymentRecord->id,
                'customer_email' => $validated['customer_email'] ?? $request->user()?->email,
                'customer' => [
                    'name' => $validated['customer_name'] ?? $request->user()?->name,
                    'email' => $validated['customer_email'] ?? $request->user()?->email,
                    'phone' => $phone,
                    'reference' => 'CHK-' . now()->format('YmdHis'),
                ],
            ]);
        } catch (RuntimeException|InvalidArgumentException $exception) {
            $paymentRecord->update([
                'payment_status' => 'failed',
                'callback_data' => ['error' => $exception->getMessage()],
            ]);
            $order->update(['status' => 'cancelled']);

            return back()
                ->withInput()
                ->with('failed', $exception->getMessage());
        }

        $paymentRecord->update([
            'bill_code' => $payload['bill_code'],
            'callback_data' => $payload['callback_data'],
        ]);

        return redirect()
            ->away($payload['redirect_url'])
            ->with('checkout_payload', $payload);
    }

    public function stripeSuccess(Request $request)
    {
        $sessionId = (string) $request->query('session_id');

        if (! $sessionId) {
            return redirect()
                ->route('user.payment-success')
                ->with('failed', 'Stripe checkout session was not provided.');
        }

        try {
            $session = (new StripeCheckoutAdapter())->retrieveCheckoutSession($sessionId);
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('user.payment-success')
                ->with('failed', $exception->getMessage());
        }

        $paymentId = (int) data_get($session, 'metadata.payment_id');
        $payment = Payment::with('order')->find($paymentId);

        $isValidPayment = $payment
            && $payment->bill_code === $sessionId
            && (int) data_get($session, 'metadata.order_id') === $payment->order_id
            && $payment->order->user_id === Auth::id()
            && (int) ($session['amount_total'] ?? 0) === (int) round($payment->payment_amount * 100)
            && ($session['payment_status'] ?? null) === 'paid';

        if (! $isValidPayment) {
            return redirect()
                ->route('user.payment-success')
                ->with('failed', 'Stripe payment could not be verified.');
        }

        $this->markPaymentSuccessful(
            $payment,
            $session['payment_intent'] ?? $sessionId,
            $session
        );

        return redirect()
            ->route('user.payment-success')
            ->with('success', 'Stripe card payment completed successfully.')
            ->with('checkout_result', [
                'status_id' => '1',
                'billcode' => $sessionId,
                'order_id' => $payment->order_id,
            ]);
    }

    public function stripeCancel(Request $request)
    {
        $orderId = (int) $request->query('order_id');
        $payment = Payment::with('order')
            ->where('order_id', $orderId)
            ->where('payment_method', 'Card')
            ->whereHas('order', fn ($query) => $query->where('user_id', Auth::id()))
            ->first();

        if ($payment && $payment->payment_status !== 'success') {
            $payment->update([
                'payment_status' => 'failed',
                'callback_data' => ['cancelled' => true],
            ]);
            $payment->order->update(['status' => 'cancelled']);
        }

        return redirect()
            ->route('user.payment-success')
            ->with('failed', 'Stripe card payment was cancelled.')
            ->with('checkout_result', [
                'status_id' => '3',
                'billcode' => $payment?->bill_code,
                'order_id' => $orderId ?: null,
            ]);
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
            $this->markPaymentSuccessful(
                $payment,
                $request->input('transaction_id'),
                $request->all()
            );

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
            'Card' => new CardFactory(),
            'FPX' => new FPXFactory(),
            default => throw new InvalidArgumentException('Invalid Payment Type'),
        };
    }

    private function markPaymentSuccessful(Payment $payment, ?string $transactionId, array $callbackData): void
    {
        $alreadySuccessful = $payment->payment_status === 'success';

        $payment->update([
            'payment_status' => 'success',
            'transaction_id' => $transactionId,
            'callback_data' => $callbackData,
            'paid_at' => $payment->paid_at ?? now(),
        ]);

        $payment->order->update(['status' => 'paid']);

        if (! $alreadySuccessful) {
            Cart::where('user_id', $payment->order->user_id)
                ->first()
                ?->items()
                ->delete();
        }
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
