<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoeVariations extends Model
{
    use HasFactory;

    protected $table = 'shoe_variations';

    protected $fillable = [
        'shoe_id',
        'attributes',
        'stock_quantity',
        'sku_code',
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function shoe(): BelongsTo
    {
        return $this->belongsTo(Shoe::class, 'shoe_id');
    }

    public function images()
    {
        return $this->hasMany(ShoeVariationImage::class, 'shoe_variation_id');
    }
}
