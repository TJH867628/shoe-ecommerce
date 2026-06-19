<?php

namespace App\Factory;

use App\Payments\Payment;
use App\Payments\CardPayment;
//concrete creator
class CardFactory extends PaymentFactory
{
    public function createPayment(): Payment
    {
        return new CardPayment();
    }
}
