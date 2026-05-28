<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Prototype\ShoePrototype;

class Shoe extends Model implements ShoePrototype
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'shoe_name',
        'shoe_description',
        'shoe_price',
    ];

    /**
     * Variations / SKUs for this shoe.
     */
    public function variations(): HasMany
    {
        return $this->hasMany(ShoeVariations::class, 'shoe_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(ShoeOption::class, 'shoe_id');
    }

    public function images()
    {
        return $this->hasMany( ShoeImage::class );
    }

    public function cloneShoe()
    {
        //replicate is a built in laravel method that creates a copy of the model instance without saving it to the database
        //it will copy all the attributes of the shoe except the id and timestamps, allowing you to create a new shoe based on an existing one
        return $this->replicate();
    }
}
