<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    use HasFactory;

    public function client(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class);
    }

    public function pumps(): HasMany
    {
        return $this->hasMany(\App\Models\Pump::class);
    }

    public function users(): hasMany
    {
        return $this->hasMany(\App\Models\User::class, 'branch_users', 'branch_id', 'user_id');
    }
}
