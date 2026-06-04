<!DOCTYPE html>
<html>

<head>
    <title>Checkout</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 32px 16px;
            background: #f4f6f8;
            color: #111827;
            font-family: Arial, sans-serif;
        }

        .page {
            max-width: 1120px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 32px;
            letter-spacing: -0.03em;
        }

        .header p {
            margin: 8px 0 0;
            color: #6b7280;
            line-height: 1.6;
        }

        .grid {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(320px, 0.8fr);
            gap: 24px;
            align-items: start;
        }

        .panel {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }

        .section-title {
            margin: 0 0 16px;
            font-size: 18px;
            font-weight: 800;
        }

        .summary-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 16px;
            margin-top: 16px;
        }

        .field {
            margin-top: 16px;
        }

        .field-hint {
            margin: 8px 0 0;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #111827;
            font-size: 14px;
            font-weight: 700;
        }

        input,
        select,
        button {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #111827;
            font: inherit;
        }

        button {
            margin-top: 18px;
            border: none;
            background: #111827;
            color: #ffffff;
            font-weight: 800;
            cursor: pointer;
        }

        button:hover {
            background: #0f172a;
        }

        .banner,
        .payload {
            margin-top: 16px;
            padding: 14px 16px;
            border-radius: 14px;
        }

        .banner-success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .banner-failed,
        .errors {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .payload {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .payload pre {
            margin: 0;
            white-space: pre-wrap;
            word-break: break-word;
            font-family: Consolas, monospace;
            font-size: 13px;
            line-height: 1.6;
        }

        .hint {
            margin-top: 10px;
            color: #6b7280;
            font-size: 13px;
        }

        .muted {
            color: #6b7280;
        }

        @media (max-width: 860px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="header">
            <h1>Checkout</h1>
            <p>Review your order, choose a payment method, and continue to ToyyibPay sandbox.</p>
        </div>

        <div class="grid">
            <div class="panel">
                <h2 class="section-title">Payment details</h2>

                <form id="checkout-form" action="{{ route('checkout') }}" method="POST">
                    @csrf

                    <div class="field" hidden>
                        <label for="amount">Amount</label>
                        <input id="amount" type="number" name="amount" value="{{ old('amount', number_format((float) ($amount ?? 500), 2, '.', '')) }}" min="0.01" step="0.01" required>
                    </div>

                    <div class="field">
                        <label for="payment_type">Payment Method</label>
                        <select id="payment_type" name="payment_type" required>
                            <option value="FPX" @selected(old('payment_type', $paymentType ?? 'FPX' )==='FPX' )>FPX</option>
                            <option value="Card" @selected(old('payment_type', $paymentType ?? '' )==='Card' )>Card</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="customer_name">Full Name</label>
                        <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name', $customerName ?? '') }}" placeholder="John Doe" required>
                    </div>

                    <div class="field">
                        <label for="customer_email">Email</label>
                        <input id="customer_email" type="email" name="customer_email" value="{{ old('customer_email', $customerEmail ?? '') }}" placeholder="john@example.com" required>
                    </div>

                    <div class="field">
                        <label for="customer_phone">Phone Number</label>
                        <input
                            id="customer_phone"
                            type="tel"
                            name="customer_phone"
                            value="{{ old('customer_phone', $customerPhone ?? '') }}"
                            placeholder="0123456789"
                            inputmode="tel"
                            title="Enter a valid Malaysia phone number, for example 0123456789 or +60123456789."
                            required
                        >
                        <p class="field-hint">Use a Malaysia number such as 0123456789 or +60123456789. Spaces and dashes are accepted.</p>
                    </div>

                    <button type="submit">Continue to Payment</button>
                </form>

                @if($errors->any())
                <div class="errors banner">
                    <strong>Validation failed</strong>
                    <ul style="margin: 10px 0 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(session('success'))
                <div class="banner banner-success">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('failed'))
                <div class="banner banner-failed">
                    {{ session('failed') }}
                </div>
                @endif
            </div>

            <div class="panel">
                <h2 class="section-title">Order summary</h2>
                <div class="summary-box">
                    <p><strong>Subtotal:</strong> RM {{ number_format((float) ($subtotal ?? 500), 2) }}</p>
                    <p><strong>Discount:</strong> RM {{ number_format((float) ($discountAmount ?? 0), 2) }}</p>
                    <p><strong>Shipping:</strong> RM {{ number_format((float) ($shipping ?? 0), 2) }}</p>
                    <p><strong>Shipping Method:</strong> {{ ucfirst($shippingMethod ?? 'standard') }}</p>
                    <p><strong>Payment:</strong> ToyyibPay sandbox</p>
                    <p><strong>Methods:</strong> FPX or Card</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function () {
            const phoneInput = document.getElementById('customer_phone');
            const checkoutForm = document.getElementById('checkout-form');
            const malaysiaPhonePattern = /^0(?:1[0-46-9][0-9]{7,8}|[3-9][0-9]{7,8})$/;

            function normalizeMalaysiaPhone(phone) {
                let normalized = phone.replace(/[\s\-.()]/g, '');

                if (normalized.startsWith('+60')) {
                    normalized = '0' + normalized.slice(3);
                } else if (normalized.startsWith('60')) {
                    normalized = '0' + normalized.slice(2);
                }

                return normalized;
            }

            function validatePhone() {
                const normalized = normalizeMalaysiaPhone(phoneInput.value);
                const isValid = malaysiaPhonePattern.test(normalized);
                phoneInput.setCustomValidity(isValid ? '' : 'Enter a valid Malaysia phone number, for example 0123456789 or +60123456789.');
                return isValid;
            }

            phoneInput?.addEventListener('input', validatePhone);
            checkoutForm?.addEventListener('submit', function (event) {
                if (!validatePhone()) {
                    event.preventDefault();
                    phoneInput.reportValidity();
                }
            });
        })();
    </script>
</body>

</html>
