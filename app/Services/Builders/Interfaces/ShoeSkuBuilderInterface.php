<?php

namespace App\Services\Builders\Interfaces;

interface ShoeSkuBuilderInterface
{
    public function reset();
    /**
     * Add SKU combination.
     */
    public function addSku(
        int $shoeId,
        array $selectedOptions,
        int $stock,
    ): self;

    /**
     * Build final SKU collection.
     */
    public function build(): array;

    
}
