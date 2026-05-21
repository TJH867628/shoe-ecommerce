<?php

namespace App\Services\Builders\Directors;

use App\Services\Builders\Interfaces\ShoeSkuBuilderInterface;

class ShoeSkuDirector
{
    protected ShoeSkuBuilderInterface $builder;

    public function __construct(
        ShoeSkuBuilderInterface $builder
    )
    {
        $this->builder = $builder;
    }

    public function changeBuilder(
        ShoeSkuBuilderInterface $builder
    )
    {
        $this->builder = $builder;
    }

    public function buildSku(
        int $shoeId,
        array $attributes,
        int $stock
    )
    {
        $this->builder->reset();

        $this->builder->addSku(
            $shoeId,
            $attributes,
            $stock
        );

        return $this->builder->build();
    }
}
