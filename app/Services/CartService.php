<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ShoeVariations;
use App\Observers\DiscountCalculator;
use App\Observers\PriceDisplay;
use App\Observers\ShippingCalculator;

class CartService
{
    public function __construct(
        private DiscountCalculator $discountCalculator,
        private PriceDisplay $priceDisplay,
        private ShippingCalculator $shippingCalculator
    ) {
    }

    /**
     * Add item to cart and trigger observer notifications
     */
    public function addItem(Cart $cart, int $shoeVariationId, int $quantity): CartItem
    {
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'shoe_variation_id' => $shoeVariationId,
            'quantity' => $quantity,
        ]);

        // Notify observers about the cart change
        $cart->notify();

        return $cartItem;
    }

    /**
     * Update item quantity in cart and trigger observer notifications
     */
    public function updateItem(CartItem $cartItem, int $quantity): CartItem
    {
        $cartItem->update(['quantity' => $quantity]);

        // Notify observers about the cart change
        $cartItem->cart->notify();

        return $cartItem;
    }

    /**
     * Remove item from cart and trigger observer notifications
     */
    public function removeItem(CartItem $cartItem): bool
    {
        $cart = $cartItem->cart;
        $result = $cartItem->delete();

        // Notify observers about the cart change
        $cart->notify();

        return $result;
    }

    /**
     * Get complete cart summary with all calculated values
     */
    public function getCartSummary(Cart $cart): array
    {
        // Ensure observers are attached and notified
        $cart->notify();

        $cartData = $cart->getCartData();

        $this->priceDisplay->update(array_merge($cartData, [
            'discount' => $this->discountCalculator->getDiscountAmount(),
            'shipping' => $this->shippingCalculator->getShippingCost(),
        ]));

        return [
            'cart_id' => $cart->id,
            'items' => $cart->items()->with('variation')->get(),
            'pricing' => $this->priceDisplay->getPriceBreakdown(),
            'discount' => [
                'percentage' => $this->discountCalculator->getDiscountPercentage(),
                'amount' => $this->discountCalculator->getDiscountAmount(),
            ],
            'shipping' => [
                'method' => $this->shippingCalculator->getShippingMethod(),
                'cost' => $this->shippingCalculator->getShippingCost(),
            ],
        ];
    }

    /**
     * Clear cart and trigger observer notifications
     */
    public function clearCart(Cart $cart): bool
    {
        $result = $cart->items()->delete();

        // Notify observers about the cart change
        $cart->notify();

        return $result > 0;
    }
}
