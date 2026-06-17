<?php
//Product Interface
namespace App\Payments;

interface Payment
{
    public function pay(float $amount, array $data = []): array;

    public function driverCode(): string;

    public function methodLabel(): string;

    public function paymentChannel(): int;
}
