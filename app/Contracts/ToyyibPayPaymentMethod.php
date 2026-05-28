<?php

namespace App\Contracts;

interface ToyyibPayPaymentMethod
{
    public function driverCode(): string;

    public function methodLabel(): string;

    public function paymentChannel(): int;
}