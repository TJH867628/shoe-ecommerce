<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoeOption extends Model
{
    protected $table = 'shoe_options';

    protected $fillable = [
        'shoe_id',
        'option_name',
    ];

    public function shoe()
    {
        return $this->belongsTo(Shoe::class, 'shoe_id');
    }
}
