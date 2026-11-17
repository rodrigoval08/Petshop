<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\SalesRecord;
use App\Models\Venta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $ventas = Venta::withCount('detalles')
        ->orderByDesc('id')
        ->paginate(10);

        return view('ventas.index', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    /** Carrito de nueva venta  */ 
    public function create(): View
    {
        $productos = Producto::orderBy('name')->get();

        // Datos para que el carrito (JavaScript) calcule subtotales
        $catalogo = $productos->mapWithKeys(fn ($p) => [
            $p->id => [
                'id'    => $p->id,
                'sku'   => $p->sku,
                'name'  => $p->name,
                'price' => (float) $p->price,
                'stock' => $p->stock,
            ],
        ]);

        return view('ventas.create', compact('productos', 'catalogo'));
    }

    
    /**  RF06 + RF07 + RNF03: venta transaccional */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
        ], [
            'items.required'              => 'Agrega al menos un producto a la venta.',
            'items.min'                   => 'Agrega al menos un producto a la venta.',
            'items.*.product_id.required' => 'Hay una línea sin producto.',
            'items.*.product_id.exists'   => 'Uno de los productos ya no existe.',
            'items.*.quantity.required'   => 'Indica la cantidad de cada producto.',
            'items.*.quantity.integer'    => 'La cantidad debe ser un número entero.',
            'items.*.quantity.min'        => 'La cantidad mínima es 1.',
        ]);

        // Si el mismo producto aparece dos veces, se suman las cantidades
        $lineas = collect($datos['items'])
            ->groupBy('product_id')
            ->map(fn ($grupo) => (int) $grupo->sum('quantity'))
            ->sortKeys(); // siempre se bloquea en el mismo orden (evita bloqueos cruzados)

        // Todo o nada: si algo falla, DB::transaction hace rollback automático
        $venta = DB::transaction(function () use ($lineas) {
            $venta = Venta::create(['total' => 0]);
            $total = 0;

            foreach ($lineas as $productoId => $cantidad) {
                // RNF03: bloquea la fila mientras se descuenta el stock
                $producto = Producto::lockForUpdate()->findOrFail($productoId);

                // RF07: stock insuficiente => excepción de validación
                if ($cantidad > $producto->stock) {
                    throw ValidationException::withMessages([
                        'items' => "Stock insuficiente para «{$producto->name}»: pediste {$cantidad} y solo quedan {$producto->stock}.",
                    ]);
                }

                $stockAntes   = $producto->stock;
                $stockDespues = $stockAntes - $cantidad;
                $subtotal     = round((float) $producto->price * $cantidad, 2);

                $producto->update(['stock' => $stockDespues]);

                // RF08: queda auditado el stock antes y después
                SalesRecord::create([
                    'sale_id'      => $venta->id,
                    'product_id'   => $producto->id,
                    'quantity'     => $cantidad,
                    'stock_before' => $stockAntes,
                    'stock_after'  => $stockDespues,
                    'total_price'  => $subtotal,
                ]);

                $total += $subtotal;
            }

            $venta->update(['total' => $total]);

            return $venta;
        });

        return redirect()
            ->route('ventas.show', $venta)
            ->with('exito', 'Venta registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    /** Detalle de una venta */
    public function show(Venta $venta): View
    {
        $venta->load('detalles.producto');

        return view('ventas.show', compact('venta'));
    }

}