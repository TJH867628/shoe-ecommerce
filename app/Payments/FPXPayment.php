<?php
//Concrete Product
namespace App\Payments;
class FPXPayment implements Payment
{
    public function pay(float $amount)
    {
        return "Paid RM " . number_format($amount, 2) . " using FPX.";
    }
}