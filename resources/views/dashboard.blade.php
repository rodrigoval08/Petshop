@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
    <div class="panel">
        <h2>Bienvenido, {{ auth()->user()->name }} 👋</h2>
        <p style="color:#6b7280; margin-top:6px;">{{ now()->translatedFormat('l d \d\e F \d\e Y') }}</p>
    </div>

    {{-- RF09: indicadores --}}
    <div class="tarjetas">
        <div class="tarjeta">
            <div class="etiqueta">Total de productos</div>
            <div class="valor">{{ $totalProductos }}</div>
        </div>

        <div class="tarjeta verde">
            <div class="etiqueta">Total de clientes</div>
            <div class="valor">{{ $totalClientes }}</div>
        </div>

        <div class="tarjeta morada">
            <div class="etiqueta">Total de ventas</div>
            <div class="valor">{{ $totalVentas }}</div>
            <small style="color:#6b7280;">S/ {{ number_format($montoVentas, 2) }} acumulados</small>
        </div>

        <div class="tarjeta roja">
            <div class="etiqueta">Productos con stock crítico (≤ {{ \App\Models\Producto::STOCK_CRITICO }})</div>
            <div class="valor">{{ $stockCritico }}</div>
        </div>
    </div>

    {{-- RF10: alertas de stock bajo --}}
    <div class="panel">
        <div class="panel-titulo">
            <h2>⚠️ Productos por agotarse</h2>
            <a href="{{ route('productos.index') }}" class="btn btn-secundario btn-sm">Ver catálogo</a>
        </div>

        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alertas as $producto)
                        <tr>
                            <td>{{ $producto->sku }}</td>
                            <td><strong>{{ $producto->name }}</strong></td>
                            <td>{{ $producto->categoria->name }}</td>
                            <td>{{ $producto->stock }}</td>
                            <td>@include('productos._badge', ['stock' => $producto->stock])</td>
                            <td>
                                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-primario btn-sm">Reponer</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="vacio">✅ No hay productos con stock crítico.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($stockCritico > $alertas->count())
            <p style="color:#6b7280; font-size:13px; margin-top:12px;">
                Mostrando {{ $alertas->count() }} de {{ $stockCritico }} productos en alerta.
            </p>
        @endif
    </div>

    {{-- Últimas ventas --}}
    <div class="panel">
        <div class="panel-titulo">
            <h2>Últimas ventas</h2>
            <a href="{{ route('ventas.index') }}" class="btn btn-secundario btn-sm">Ver historial</a>
        </div>

        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>N° venta</th>
                        <th>Fecha</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ultimasVentas as $venta)
                        <tr>
                            <td><strong>{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $venta->detalles_count }}</td>
                            <td>S/ {{ number_format($venta->total, 2) }}</td>
                            <td><a href="{{ route('ventas.show', $venta) }}" class="btn btn-secundario btn-sm">Ver</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="vacio">Todavía no hay ventas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection