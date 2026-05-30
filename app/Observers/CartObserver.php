<?php

namespace App\Observers;

interface CartObserver
{
    /**
     * Called when the cart is updated
     *
     * @param array $cartData The current cart data
     * @return void
     */
    public function update(array $cartData): void;

    /**
     * Optional observer name for debugging and logging.
     */
    public function getObserverName(): string;
}
