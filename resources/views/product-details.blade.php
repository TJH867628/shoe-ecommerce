<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shoe->shoe_name }} | Product Details</title>
    <style>
        :root {
            --accent: #ee4d2d;
            --accent-dark: #d73211;
            --bg: #f5f5f5;
            --text: #1f2937;
            --muted: #6b7280;
            --card: #ffffff;
            --border: #e5e7eb;
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(180deg, #fff 0%, var(--bg) 100%);
            color: var(--text);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .page {
            max-width: 1240px;
            margin: 0 auto;
            padding: 24px 16px 56px;
        }

        .header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
        }

        .brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 10px 16px;
            box-shadow: var(--shadow);
        }

        .brand-dot {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: var(--accent);
        }

        .brand-pill strong {
            color: var(--accent);
        }

        .message {
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 14px;
            border: 1px solid transparent;
        }

        .message-success {
            background: #ecfdf3;
            color: #166534;
            border-color: #86efac;
        }

        .message-error {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fca5a5;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.1fr 1.4fr;
            gap: 22px;
            align-items: start;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: var(--shadow);
        }

        .product-media {
            padding: 22px;
        }

        .image-placeholder {
            min-height: 520px;
            border-radius: 22px;
            border: 2px dashed #ffd1c7;
            background:
                radial-gradient(circle at top left, rgba(238, 77, 45, 0.12), transparent 36%),
                linear-gradient(180deg, #fff7f4, #fff);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .image-placeholder::after {
            content: "Product Image";
            position: absolute;
            bottom: 18px;
            left: 18px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--accent);
        }

        .shoe-icon {
            width: 140px;
            height: 140px;
            border-radius: 36px;
            background: rgba(238, 77, 45, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
        }

        .product-info {
            padding: 28px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #fff7f4;
            color: var(--accent);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        h1 {
            margin: 14px 0 10px;
            font-size: clamp(28px, 4vw, 42px);
            line-height: 1.05;
        }

        .brand-name {
            margin: 0 0 14px;
            font-size: 18px;
            color: var(--muted);
        }

        .price {
            display: flex;
            align-items: baseline;
            gap: 10px;
            margin: 18px 0 8px;
        }

        .price strong {
            font-size: 34px;
            color: var(--accent);
        }

        .price span {
            color: var(--muted);
        }

        .description {
            margin: 18px 0 0;
            line-height: 1.7;
            color: #374151;
            white-space: pre-line;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin: 24px 0;
        }

        .detail-box {
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: #fff;
            transition: transform 160ms ease, border-color 160ms ease;
        }

        .detail-box:hover {
            transform: translateY(-2px);
            border-color: rgba(238, 77, 45, 0.3);
        }

        .detail-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .chip {
            appearance: none;
            border: 1px solid var(--border);
            background: #fff;
            padding: 10px 14px;
            border-radius: 999px;
            color: #111827;
            font-weight: 600;
            cursor: pointer;
            transition: all 160ms ease;
        }

        .chip:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-1px);
        }

        .chip.is-selected {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
            box-shadow: 0 10px 18px rgba(238, 77, 45, 0.2);
        }

        .chip:disabled {
            cursor: not-allowed;
            opacity: 0.45;
            color: #9ca3af;
            background: #f9fafb;
            transform: none;
            box-shadow: none;
        }

        .chip:disabled:hover {
            border-color: var(--border);
            color: #9ca3af;
            transform: none;
        }

        .form-card {
            margin-top: 18px;
            padding: 22px;
            border-radius: 22px;
            border: 1px solid var(--border);
            background: linear-gradient(180deg, #fff, #fff8f5);
        }

        .form-title {
            margin: 0 0 14px;
            font-size: 18px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .field label {
            font-size: 13px;
            font-weight: 700;
            color: #374151;
        }

        .field select,
        .field input {
            width: 100%;
            padding: 13px 14px;
            border-radius: 14px;
            border: 1px solid #d1d5db;
            background: #fff;
            font-size: 15px;
            transition: border-color 160ms ease, box-shadow 160ms ease;
        }

        .field select:focus,
        .field input:focus {
            outline: none;
            border-color: rgba(238, 77, 45, 0.7);
            box-shadow: 0 0 0 4px rgba(238, 77, 45, 0.12);
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 18px;
        }

        .status-line {
            margin-top: 12px;
            font-size: 14px;
            font-weight: 700;
            color: var(--muted);
        }

        .status-line.is-ready {
            color: #166534;
        }

        .status-line.is-error {
            color: #b91c1c;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 18px;
            border-radius: 14px;
            border: 0;
            font-weight: 700;
            cursor: pointer;
            transition: transform 160ms ease, box-shadow 160ms ease, background 160ms ease;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 12px 24px rgba(238, 77, 45, 0.22);
        }

        .btn-primary:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
        }

        .section {
            margin-top: 22px;
        }

        .section-card {
            padding: 22px;
        }

        .section-head {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
        }

        .section-head h2 {
            margin: 0;
            font-size: 22px;
        }

        .section-head span {
            color: var(--muted);
            font-size: 13px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }

        th,
        td {
            text-align: left;
            padding: 14px 12px;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }

        th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            background: #fff8f5;
        }

        tbody tr:hover {
            background: #fffaf8;
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            background: #ecfdf3;
            color: #166534;
            font-weight: 700;
            font-size: 13px;
        }

        .empty-state {
            padding: 22px;
            border-radius: 18px;
            border: 1px dashed #f5c2b8;
            background: #fff7f4;
            color: var(--muted);
        }

        @media (max-width: 960px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .image-placeholder {
                min-height: 360px;
            }
        }

        @media (max-width: 720px) {
            .page {
                padding-inline: 12px;
            }

            .product-info,
            .product-media,
            .section-card {
                padding: 18px;
            }

            .detail-grid,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="brand-pill">
                <span class="brand-dot"></span>
                <span><strong>Shopee</strong> style product details</span>
            </div>
            <div class="brand-pill">
                <span>SKU catalog for {{ $shoe->brand?->brand_name ?? 'Unknown Brand' }}</span>
            </div>
        </div>

        @if (session('success'))
            <div class="message message-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="message message-error">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="message message-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="hero">
            <section class="card product-media">
                <div class="image-placeholder">
                    <div class="shoe-icon" aria-hidden="true">
                        <svg width="72" height="72" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 15.5C3 16.8807 4.11929 18 5.5 18H18.5C19.8807 18 21 16.8807 21 15.5V13.5C21 12.6716 20.3284 12 19.5 12H15.5L12.5 9H8.5L6.5 7H3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.5 18H4.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </section>

            <section class="card product-info">
                <span class="eyebrow">Available now</span>
                <h1>{{ $shoe->shoe_name }}</h1>
                <p class="brand-name">{{ $shoe->brand?->brand_name ?? 'Unknown Brand' }}</p>

                <div class="price">
                    <strong>RM {{ number_format($shoe->shoe_price, 2) }}</strong>
                    <span>Retail price</span>
                </div>

                <p class="description">{{ $shoe->shoe_description }}</p>

                <div class="form-card">
                    <h2 class="form-title">Choose size and color</h2>
                    <form method="POST" action="{{ route('cart.add') }}" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="shoe_id" value="{{ $shoe->id }}">
                        <input type="hidden" name="size" id="selected-size" value="{{ old('size') }}">
                        <input type="hidden" name="color" id="selected-color" value="{{ old('color') }}">

                        <div class="form-grid">
                            <div class="field">
                                <label>Size</label>
                                <div class="chips" id="size-option-group">
                                    @forelse ($sizes as $size)
                                        @php
                                            $sizeHasStock = collect($variationMatrix)->contains(function ($variation) use ($size) {
                                                return $variation['size'] === $size && $variation['stock_quantity'] > 0;
                                            });
                                        @endphp
                                        <button
                                            type="button"
                                            class="chip option-pill"
                                            data-option-group="size"
                                            data-value="{{ $size }}"
                                            @disabled(! $sizeHasStock)
                                        >
                                            {{ $size }}
                                        </button>
                                    @empty
                                        <span class="chip" aria-disabled="true">No sizes available</span>
                                    @endforelse
                                </div>
                            </div>

                            <div class="field">
                                <label>Color</label>
                                <div class="chips" id="color-option-group">
                                    @forelse ($colors as $color)
                                        @php
                                            $colorHasStock = collect($variationMatrix)->contains(function ($variation) use ($color) {
                                                return $variation['color'] === $color && $variation['stock_quantity'] > 0;
                                            });
                                        @endphp
                                        <button
                                            type="button"
                                            class="chip option-pill"
                                            data-option-group="color"
                                            data-value="{{ $color }}"
                                            @disabled(! $colorHasStock)
                                        >
                                            {{ $color }}
                                        </button>
                                    @empty
                                        <span class="chip" aria-disabled="true">No colors available</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="status-line" id="stock-status">Choose a size and color to see availability.</div>

                        <div class="actions">
                            <button type="submit" class="btn btn-primary" id="add-to-cart-button" disabled>Add To Cart</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        <section class="card section section-card">
            <div class="section-head">
                <h2>Variation stock table</h2>
                <span>SKU, color, size, and stock quantity</span>
            </div>

            @if ($shoe->variations->isNotEmpty())
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>SKU Code</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Stock Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shoe->variations as $variation)
                                <tr>
                                    <td>{{ $variation->sku_code }}</td>
                                    <td>{{ $variation->attributes['color'] ?? '-' }}</td>
                                    <td>{{ $variation->attributes['size'] ?? '-' }}</td>
                                    <td><span class="stock-badge">{{ $variation->stock_quantity }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">No variations are available for this product yet.</div>
            @endif
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const variations = @json($variationMatrix);
            const sizeButtons = Array.from(document.querySelectorAll('[data-option-group="size"]'));
            const colorButtons = Array.from(document.querySelectorAll('[data-option-group="color"]'));
            const selectedSizeInput = document.getElementById('selected-size');
            const selectedColorInput = document.getElementById('selected-color');
            const statusLine = document.getElementById('stock-status');
            const submitButton = document.getElementById('add-to-cart-button');

            let selectedSize = selectedSizeInput.value || '';
            let selectedColor = selectedColorInput.value || '';

            function findMatch(size, color) {
                return variations.find(function (variation) {
                    return variation.size === size && variation.color === color;
                });
            }

            function updateButtons() {
                sizeButtons.forEach(function (button) {
                    const value = button.dataset.value;
                    const hasStock = variations.some(function (variation) {
                        if (variation.size !== value || Number(variation.stock_quantity) <= 0) {
                            return false;
                        }

                        return !selectedColor || variation.color === selectedColor;
                    });

                    button.disabled = !hasStock;
                    button.classList.toggle('is-selected', selectedSize === value);
                });

                colorButtons.forEach(function (button) {
                    const value = button.dataset.value;
                    const hasStock = variations.some(function (variation) {
                        if (variation.color !== value || Number(variation.stock_quantity) <= 0) {
                            return false;
                        }

                        return !selectedSize || variation.size === selectedSize;
                    });

                    button.disabled = !hasStock;
                    button.classList.toggle('is-selected', selectedColor === value);
                });
            }

            function updateStatus() {
                if (!selectedSize || !selectedColor) {
                    statusLine.textContent = 'Choose a size and color to see availability.';
                    statusLine.className = 'status-line';
                    submitButton.disabled = true;
                    return;
                }

                const match = findMatch(selectedSize, selectedColor);

                if (!match) {
                    statusLine.textContent = 'This size and color combination is unavailable.';
                    statusLine.className = 'status-line is-error';
                    submitButton.disabled = true;
                    return;
                }

                if (Number(match.stock_quantity) <= 0) {
                    statusLine.textContent = 'This variation is out of stock.';
                    statusLine.className = 'status-line is-error';
                    submitButton.disabled = true;
                    return;
                }

                statusLine.textContent = 'In stock: ' + match.stock_quantity + ' available.';
                statusLine.className = 'status-line is-ready';
                submitButton.disabled = false;
            }

            function syncSelection(group, value) {
                if (group === 'size') {
                    selectedSize = value;
                    selectedSizeInput.value = value;
                }

                if (group === 'color') {
                    selectedColor = value;
                    selectedColorInput.value = value;
                }

                updateButtons();
                updateStatus();
            }

            sizeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    if (button.disabled) {
                        return;
                    }

                    const nextValue = selectedSize === button.dataset.value ? '' : button.dataset.value;
                    syncSelection('size', nextValue);
                });
            });

            colorButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    if (button.disabled) {
                        return;
                    }

                    const nextValue = selectedColor === button.dataset.value ? '' : button.dataset.value;
                    syncSelection('color', nextValue);
                });
            });

            updateButtons();
            updateStatus();

            if (selectedSize) {
                syncSelection('size', selectedSize);
            }

            if (selectedColor) {
                syncSelection('color', selectedColor);
            }
        });
    </script>
</body>
</html>