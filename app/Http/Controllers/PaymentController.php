<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factory\FPXFactory;
use App\Factory\CardFactory;
use Exception;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $amount = $request->amount;

        if ($request->payment_type == 'FPX')
        {
            $factory = new FPXFactory();
        }
        elseif ($request->payment_type == 'Card')
        {
            $factory = new CardFactory();
        }
        else
        {
            throw new Exception(
                "Invalid Payment Type"
            );
        }

        $result = $factory->processPayment($amount);

        return redirect()
            ->back()
            ->with('success', $result);
    }
}