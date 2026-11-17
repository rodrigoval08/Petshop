<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesRecord extends Model
{
    protected $table = 'sales_records';

    public $timestamps = false;

    protected $fillable = [
        'sale_id', 'product_id', 'quantity', 'stock_before', 'stock_after', 'total_price',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'created_at'  => 'datetime',
    ];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'sale_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'product_id');
    }
}