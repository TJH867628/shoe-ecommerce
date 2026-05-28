<?php
//Concrete Product
namespace App\Payments;

class CardPayment implements Payment
{
    public function pay(float $amount)
    {
        return "Paid RM " . number_format($amount, 2) . " using Card.";
    }
}