<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pet extends Model
{
    public const ESPECIES = ['Perro', 'Gato', 'Ave', 'Conejo', 'Hámster', 'Otro'];

    protected $table = 'pets';

    protected $fillable = ['customer_id', 'name', 'species', 'breed'];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}