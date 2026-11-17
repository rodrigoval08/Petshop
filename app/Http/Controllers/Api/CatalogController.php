<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    public function index(): JsonResponse
    {
        $productos = Producto::with('categoria')
            ->orderBy('name')
            ->get()
            ->map(function ($item) {
                return [
                    'id'          => (string) $item->id,
                    'name'        => $item->name,
                    'category'    => $item->categoria ? $item->categoria->name : 'General',
                    'price'       => (float) $item->price,
                    // Como tu tabla products no tiene columna de foto, ponemos una imagen por defecto de mascota
                    'imageUrl'    => $item->image ??'',
                    'description' => 'Código SKU: ' . $item->sku . ' • Producto oficial disponible en PetCare.',
                    'stock'       => (int) $item->stock,
                ];
            });

        return response()->json($productos);
    }
}