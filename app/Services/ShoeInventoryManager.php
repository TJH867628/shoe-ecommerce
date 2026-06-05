<?php

namespace App\Services;

use App\Models\ShoeVariations;

class ShoeInventoryManager
{
    private static ?ShoeInventoryManager $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): ShoeInventoryManager
    {
        if (self::$instance === null) {
            self::$instance = new ShoeInventoryManager();
        }

        return self::$instance;
    }

    public function findAvailableVariation(int $shoeId, int $variationId): ?ShoeVariations
    {
        $variation = ShoeVariations::where('shoe_id', $shoeId)
            ->whereKey($variationId)
            ->first();

        if (! $variation || $variation->stock_quantity <= 0) {
            return null;
        }

        return $variation;
    }
}
