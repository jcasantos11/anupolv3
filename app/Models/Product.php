<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public function pumps(): HasMany
    {
        return $this->hasMany(\App\Models\Pump::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(\App\Models\Product::class);
    }

    public function readings(): HasMany
    {
        return $this->hasMany(\App\Models\Reading::class);
    }
}
