<?php

namespace App\Services\Builders\Interfaces;

interface CustomerShoeBuilderInterface
{
    public function reset();

    public function setAttribute(
        string $key,
        string $value
    );

    public function getProduct();
}
