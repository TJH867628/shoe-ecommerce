<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Cart;
use App\Observers\DiscountCalculator;
use App\Observers\PriceDisplay;
use App\Observers\ShippingCalculator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register cart observers as singletons
        $this->app->singleton(DiscountCalculator::class);
        $this->app->singleton(PriceDisplay::class);
        $this->app->singleton(ShippingCalculator::class);

        // When a cart is created, attach observers to notify about changes
        Cart::creating(function ($cart) {
            return true; // Allow creation
        });

        Cart::created(function ($cart) {
            $this->attachObserversToCart($cart);
        });

        Cart::retrieved(function ($cart) {
            $this->attachObserversToCart($cart);
        });
    }

    /**
     * Attach observers to a cart instance
     */
    private function attachObserversToCart(Cart $cart): void
    {
        // Only attach if cart has a valid user (skip for test carts)
        if ($cart->user_id !== null) {
            $cart->attach($this->app->make(DiscountCalculator::class))
                 ->attach($this->app->make(PriceDisplay::class))
                 ->attach($this->app->make(ShippingCalculator::class));
        }
    }
}
