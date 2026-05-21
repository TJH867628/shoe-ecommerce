<?php

namespace App\Services\Builders\Directors;

use App\Services\Builders\Interfaces\CustomerShoeBuilderInterface;

class CustomerShoeDirector
{
    protected CustomerShoeBuilderInterface $builder;

    public function __construct(
        CustomerShoeBuilderInterface $builder
    )
    {
        $this->builder = $builder;
    }

    public function changeBuilder(
        CustomerShoeBuilderInterface $builder
    )
    {
        $this->builder = $builder;
    }

    public function buildProduct(
        array $attributes
    )
    {
        $this->builder->reset();

        foreach (
            $attributes as $key => $value
        ) {

            $this->builder->setAttribute(
                $key,
                $value
            );
        }

        return $this->builder->getProduct();
    }
}
