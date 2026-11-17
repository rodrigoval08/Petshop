<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = ['first_name', 'phone', 'email'];

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }
}