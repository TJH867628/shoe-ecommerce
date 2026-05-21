<?php

namespace App\Services\Builders\Interfaces;

interface ShoeBuilderInterface
{
    /**
     * Set brand ID.
     */
    public function setBrand(int $brandId): self;

    /**
     * Set shoe name.
     */
    public function setName(string $name): self;

    /**
     * Set shoe description.
     */
    public function setDescription(string $description): self;

    /**
     * Set base price.
     */
    public function setBasePrice(float $price): self;

    /**
     * Build final shoe object.
     */
    public function build(): array;
    public function reset();
}
