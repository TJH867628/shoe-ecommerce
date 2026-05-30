<?php

namespace App\Models;

use App\Observers\CartObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'shopping_carts';

    protected $fillable = [
        'user_id',
    ];

    /**
     * @var CartObserver[]
     */
    private array $observers = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    /**
     * Attach an observer to the cart
     */
    public function attach(CartObserver $observer): self
    {
        $this->observers[] = $observer;
        return $this;
    }

    /**
     * Detach an observer from the cart
     */
    public function detach(CartObserver $observer): self
    {
        $this->observers = array_filter(
            $this->observers,
            fn($obs) => $obs !== $observer
        );
        return $this;
    }

    /**
     * Notify all observers about cart changes
     */
    public function notify(): void
    {
        $cartData = $this->getCartData();
        foreach ($this->observers as $observer) {
            $observer->update($cartData);
        }
    }

    /**
     * Get current cart data for observers
     */
    public function getCartData(): array
    {
        $items = $this->items()->with(['variation.shoe.brand', 'variation.images'])->get();
        $subtotal = $items->sum(function ($item) {
            return ($item->variation->shoe->shoe_price ?? 0) * $item->quantity;
        });

        return [
            'cart_id' => $this->id,
            'subtotal' => round($subtotal, 2),
            'item_count' => $items->sum('quantity'),
            'items_qty' => $items->count(),
        ];
    }
}
