<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    protected $table = 'sales';

    public $timestamps = false;

    protected $fillable = ['total'];

    protected $casts = [
        'total'      => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function detalles(): HasMany
    {
        return $this->hasMany(SalesRecord::class, 'sale_id');
    }
}