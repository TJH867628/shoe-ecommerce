<?php

namespace App\Observers;
use Illuminate\Support\Facades\Log;

class PriceDisplay implements CartObserver
{
    private float $subtotal = 0;
    private float $discount = 0;
    private float $shipping = 0;
    private float $total = 0;

    /**
     * Update and display the price breakdown
     */
    public function update(array $cartData): void
    {
        $this->subtotal = $cartData['subtotal'] ?? 0;
        $this->discount = $cartData['discount'] ?? 0;
        $this->shipping = $cartData['shipping'] ?? 0;
        $this->total = $this->subtotal - $this->discount + $this->shipping;

        // Notify that prices have been updated
        Log::info('Price display updated', [
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'shipping' => $this->shipping,
            'total' => $this->total,
        ]);
    }

    public function getPriceBreakdown(): array
    {
        return [
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'shipping' => $this->shipping,
            'total' => $this->total,
        ];
    }

    public function getObserverName(): string
    {
        return 'PriceDisplay';
    }
}
