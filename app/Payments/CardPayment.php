<?php
//Concrete Product
namespace App\Payments;

class CardPayment implements Payment
{
    public function pay(float $amount)
    {
        return "Paid RM " . number_format($amount, 2) . " using Card.";
    }

    public function driverCode(): string
    {
        return 'CreditCard';
    }

    public function methodLabel(): string
    {
        return 'Card Payment';
    }

    public function paymentChannel(): int
    {
        return 1;
    }
}