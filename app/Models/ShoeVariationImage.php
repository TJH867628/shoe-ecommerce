<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoeVariationImage extends Model
{
    protected $fillable = [
        'shoe_variation_id',
        'image_path'
    ];

    public function variation()
    {
        return $this->belongsTo(
            ShoeVariations::class,
            'shoe_variation_id'
        );
    }
}