<?php
//Product Interface
namespace App\Payments;

interface Payment
{
    public function pay(float $amount);
}