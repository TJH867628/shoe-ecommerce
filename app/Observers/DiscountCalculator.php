<?php

namespace App\Observers;
use Illuminate\Support\Facades\Log;

class DiscountCalculator implements CartObserver
{
    private float $discountPercentage = 0;
    private float $discountAmount = 0;

    /**
     * Calculate discount based on cart subtotal
     * - 10% off for orders >= RM 500
     * - 5% off for orders >= RM 200
     */
    public function update(array $cartData): void
    {
        $subtotal = $cartData['subtotal'] ?? 0;
        $this->discountPercentage = 0;
        $this->discountAmount = 0;

        if ($subtotal >= 500) {
            $this->discountPercentage = 10;
        } elseif ($subtotal >= 200) {
            $this->discountPercentage = 5;
        }

        if ($this->discountPercentage > 0) {
            $this->discountAmount = round($subtotal * ($this->discountPercentage / 100), 2);
        }

        // Notify that discount has been calculated
        Log::info('Discount calculated', [
            'subtotal' => $subtotal,
            'discount_percentage' => $this->discountPercentage,
            'discount_amount' => $this->discountAmount,
        ]);
    }

    public function getDiscountPercentage(): float
    {
        return $this->discountPercentage;
    }

    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    public function getObserverName(): string
    {
        return 'DiscountCalculator';
    }
}
