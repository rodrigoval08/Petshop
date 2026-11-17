<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    public const STOCK_CRITICO = 5;

    protected $table = 'products';

    protected $fillable = ['category_id', 'sku', 'name', 'price', 'stock', 'image_path', 'image'];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'category_id');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(SalesRecord::class, 'product_id');
    }

// URL pública de la imagen (o null si no tiene)
    public function imagenUrl(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    // RF05: busca por nombre o SKU
    public function scopeBuscar(Builder $query, ?string $termino): Builder
    {
        if (!$termino) {
            return $query;
        }

        return $query->where(function ($q) use ($termino) {
            $q->where('name', 'like', "%{$termino}%")
            ->orWhere('sku', 'like', "%{$termino}%");
        });
    }

    // RF05: filtra por categoría
    public function scopeDeCategoria(Builder $query, int|string|null $categoriaId): Builder
    {
        return $categoriaId ? $query->where('category_id', $categoriaId) : $query;
    }

    // RF09: stock crítico (<= 5)
    public function scopeCritico(Builder $query): Builder
    {
        return $query->where('stock', '<=', self::STOCK_CRITICO);
    }

    public function esCritico(): bool
    {
        return $this->stock <= self::STOCK_CRITICO;
    }
}