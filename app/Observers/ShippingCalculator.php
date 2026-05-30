<?php

namespace App\Observers;
use Illuminate\Support\Facades\Log;

class ShippingCalculator implements CartObserver
{
    private float $shippingCost = 0;
    private string $shippingMethod = 'standard';

    /**
     * Calculate shipping cost based on cart conditions
     * - Free shipping for orders >= RM 300
     * - RM 15 for standard shipping (3-5 days)
     * - RM 25 for express shipping (1-2 days)
     */
    public function update(array $cartData): void
    {
        $subtotal = $cartData['subtotal'] ?? 0;
        $itemCount = $cartData['item_count'] ?? 0;
        $this->shippingMethod = 'standard';
        $this->shippingCost = 0;

        // Free shipping for orders >= RM 500
        if ($subtotal >= 500) {
            $this->shippingCost = 0;
            $this->shippingMethod = 'free';
        } else {
            // Base shipping cost
            $this->shippingCost = 15;
            
            // Add extra charge for high item count
            if ($itemCount > 10) {
                $this->shippingCost += ($itemCount - 10) * 0.50;
            }
        }

        // Notify that shipping has been calculated
        Log::info('Shipping calculated', [
            'subtotal' => $subtotal,
            'item_count' => $itemCount,
            'shipping_method' => $this->shippingMethod,
            'shipping_cost' => $this->shippingCost,
        ]);
    }

    public function getShippingCost(): float
    {
        return $this->shippingCost;
    }

    public function getShippingMethod(): string
    {
        return $this->shippingMethod;
    }

    public function getObserverName(): string
    {
        return 'ShippingCalculator';
    }
}
