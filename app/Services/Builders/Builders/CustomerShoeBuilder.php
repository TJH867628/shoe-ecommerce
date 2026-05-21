<?php

namespace App\Services\Builders\Builders;

use App\Models\Shoe;
use App\Services\Builders\Interfaces\CustomerShoeBuilderInterface;

class CustomerShoeBuilder implements CustomerShoeBuilderInterface
{
    protected Shoe $product;

    public function reset()
    {
        $this->product = new Shoe();
    }

    public function setAttribute(
        string $key,
        string $value
    )
    {
        if (!isset($this->product->attributes)) {
            $this->product->attributes = [];
        }

        $attributes = $this->product->attributes;

        $attributes[$key] = $value;

        $this->product->attributes = $attributes;
    }

    public function getProduct()
    {
        return $this->product;
    }
}
