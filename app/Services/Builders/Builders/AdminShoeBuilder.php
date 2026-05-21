<?php

namespace App\Services\Builders\Builders;

use App\Services\Builders\Interfaces\ShoeBuilderInterface;

class AdminShoeBuilder implements ShoeBuilderInterface
{
    /**
     * Store shoe data.
     */
    protected array $shoeData = [];

    public function reset()
    {
        $this->shoeData = [];
    }
    /**
     * Set brand ID.
     */
    public function setBrand(int $brandId): self
    {
        $this->shoeData['brand_id'] = $brandId;

        return $this;
    }

    /**
     * Set shoe name.
     */
    public function setName(string $name): self
    {
        $this->shoeData['shoe_name'] = $name;

        return $this;
    }

    /**
     * Set shoe description.
     */
    public function setDescription(string $description): self
    {
        $this->shoeData['shoe_description'] = $description;

        return $this;
    }

    /**
     * Set base price.
     */
    public function setBasePrice(float $price): self
    {
        $this->shoeData['shoe_price'] = $price;

        return $this;
    }

    /**
     * Return final built shoe object.
     */
    public function build(): array
    {
        return $this->shoeData;
    }
}
