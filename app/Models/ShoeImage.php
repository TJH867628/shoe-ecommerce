<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoeImage extends Model
{
    protected $fillable = [
        'shoe_id',
        'image_path',
        'is_cover',
        'sort_order'
    ];

    public function shoe()
    {
        return $this->belongsTo(
            Shoe::class
        );
    }
}