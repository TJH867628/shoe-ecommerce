<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Shoes associated with this category (if a pivot table exists).
     */
    public function shoes(): BelongsToMany
    {
        return $this->belongsToMany(Shoe::class);
    }
}
