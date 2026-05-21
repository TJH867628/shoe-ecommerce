<?php

namespace App\Services\Builders\Directors;

use App\Services\Builders\Interfaces\ShoeBuilderInterface;

class ShoeDirector
{
    protected ShoeBuilderInterface $builder;

    public function __construct(
        ShoeBuilderInterface $builder
    )
    {
        $this->builder = $builder;
    }

    public function changeBuilder(
        ShoeBuilderInterface $builder
    )
    {
        $this->builder = $builder;
    }

    public function buildShoe(
        int $brandId,
        string $shoeName,
        string $description,
        float $price
    )
    {
        $this->builder->reset();

        $this->builder->setBrand($brandId);

        $this->builder->setName($shoeName);

        $this->builder->setDescription($description);

        $this->builder->setBasePrice($price);

        return $this->builder->build();
    }
}
