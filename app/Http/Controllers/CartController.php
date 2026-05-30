<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ShoeVariations;
use App\Models\Shoe;
use App\Services\CartService;
use App\Observers\DiscountCalculator;
use App\Observers\PriceDisplay;
use App\Observers\ShippingCalculator;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private DiscountCalculator $discountCalculator,
        private PriceDisplay $priceDisplay,
        private ShippingCalculator $shippingCalculator
    ) {
    }

    /**
     * Show the shopping cart
     */
    public function show()
    {
        $userId = Auth::id();

        if (! $userId) {
            return redirect()->route('login')->with('error', 'Please log in to view your cart.');
        }

        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        // Get cart summary with observer data
        $summary = $this->cartService->getCartSummary($cart);

        // Build list of shoe IDs currently in cart to exclude from recommendations
        $cart->load('items.variation.shoe');
        $excludedShoeIds = $cart->items->map(function ($i) {
            return $i->variation->shoe?->id ?? null;
        })->filter()->unique()->values()->all();

        // Recommend up to 6 other shoes (prefer same brand if possible)
        $recommended = Shoe::with(['brand', 'images', 'variations'])
            ->when(count($excludedShoeIds) > 0, function ($q) use ($excludedShoeIds) {
                $q->whereNotIn('id', $excludedShoeIds);
            })
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('user.cart', [
            'cartItems' => $summary['items'],
            'subtotal' => $summary['pricing']['subtotal'],
            'discountPercentage' => $summary['discount']['percentage'],
            'discountAmount' => $summary['discount']['amount'],
            'shipping' => $summary['shipping']['cost'],
            'shippingMethod' => $summary['shipping']['method'],
            'total' => $summary['pricing']['total'],
            'recommended' => $recommended,
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'shoe_id' => ['required', 'exists:shoes,id'],
            'variation_id' => ['required', 'exists:shoe_variations,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $variation = ShoeVariations::where('shoe_id', $request->shoe_id)
            ->whereKey($request->variation_id)
            ->first();

        if (
            !$variation ||
            $variation->stock_quantity <= 0
        ) {

            return back()->with(
                'error',
                'Selected variation is out of stock.'
            );
        }

        $userId = Auth::id();

        if (! $userId) {
            return redirect()
                ->route('login')
                ->with('error', 'Please log in to add items to your cart.');
        }

        $cart = Cart::firstOrCreate([
            'user_id' => $userId,
        ]);

        $this->cartService->addItem($cart, $variation->id, (int) ($request->quantity ?? 1));

        return back()->with(
            'success',
            'Added to cart successfully.'
        );
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $this->cartService->updateItem($item, $request->quantity);

        return back()->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove item from cart
     */
    public function removeItem(CartItem $item)
    {
        $this->cartService->removeItem($item);

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        $userId = Auth::id();

        if (! $userId) {
            return redirect()->route('login')->with('error', 'Please log in to clear your cart.');
        }

        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $this->cartService->clearCart($cart);

        return back()->with('success', 'Cart cleared successfully.');
    }

    public static function currentCartCount(): int
    {
        $userId = Auth::id();

        if (! $userId) {
            return 0;
        }

        $cart = Cart::where('user_id', $userId)->withCount('items')->first();

        return $cart?->items_count ?? 0;
    }
}