<?php
//Concrete Product
namespace App\Payments;

class FPXPayment implements Payment
{
    public function pay(float $amount)
    {
        return "Paid RM " . number_format($amount, 2) . " using FPX.";
    }

    public function driverCode(): string
    {
        return 'FPX';
    }

    public function methodLabel(): string
    {
        return 'FPX Payment';
    }

    public function paymentChannel(): int
    {
        return 0;
    }
}