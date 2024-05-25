<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pump extends Model
{
    use HasFactory;

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(\App\Models\Sale::class);
    }

    public function readings(): HasMany
    {
        return $this->hasMany(\App\Models\Reading::class);
    }
}
