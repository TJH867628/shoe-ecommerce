<?php
//Product Interface
namespace App\Payments;

interface Payment
{
    public function pay(float $amount);
    public function driverCode(): string;
    public function methodLabel(): string;
    public function paymentChannel(): int;
}