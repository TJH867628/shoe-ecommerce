<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_amount',
        'payment_status',
        'payment_method',
        'bill_code',
        'transaction_id',
        'callback_data',
        'paid_at',
    ];

    protected $casts = [
        'callback_data' => 'array',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}