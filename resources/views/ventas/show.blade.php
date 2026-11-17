@extends('layouts.app')

@section('titulo', 'Detalle de venta')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Venta N° {{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</h2>
            <a href="{{ route('ventas.index') }}" class="btn btn-secundario">← Volver al historial</a>
        </div>

        <p style="color:#6b7280; margin-bottom:16px;">
            Fecha: {{ $venta->created_at->format('d/m/Y H:i:s') }}
        </p>

        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Cantidad</th>
                        <th>Stock antes</th>
                        <th>Stock después</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venta->detalles as $detalle)
                        <tr>
                            <td><strong>{{ $detalle->producto->name }}</strong></td>
                            <td>{{ $detalle->producto->sku }}</td>
                            <td>{{ $detalle->quantity }}</td>
                            <td>{{ $detalle->stock_before }}</td>
                            <td>{{ $detalle->stock_after }}</td>
                            <td>S/ {{ number_format($detalle->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="text-align:right; font-size:20px; margin-top:16px;">
            Total: <strong>S/ {{ number_format($venta->total, 2) }}</strong>
        </div>
    </div>
@endsection