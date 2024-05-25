<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reading extends Model
{
    use HasFactory;

    public function pump(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Pump::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    
    public function price(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Price::class);
    }
}
