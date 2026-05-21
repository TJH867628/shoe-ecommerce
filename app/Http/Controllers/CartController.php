<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ShoeVariations;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'shoe_id' => ['required', 'exists:shoes,id'],
            'size' => ['required', 'string'],
            'color' => ['required', 'string'],
        ]);

        $attributes = [
            'size' => $request->size,
            'color' => $request->color,
        ];

        $variation = ShoeVariations::where('shoe_id', $request->shoe_id)
            ->get()
            ->first(function ($variation) use ($attributes) {

                $existingAttributes = $variation->attributes;

                ksort($existingAttributes);

                $incomingAttributes = $attributes;

                ksort($incomingAttributes);

                return $existingAttributes == $incomingAttributes;
            });

        if (
            !$variation ||
            $variation->stock_quantity <= 0
        ) {

            return back()->with(
                'error',
                'Selected variation is out of stock.'
            );
        }

        $cart = Cart::firstOrCreate([
            // 'user_id' => auth()->id()
            'user_id' => 1
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'shoe_variation_id' => $variation->id,
            'quantity' => 1
        ]);

        return back()->with(
            'success',
            'Added to cart successfully.'
        );
    }
}