@extends('layouts.app')

@section('titulo', 'Historial de ventas')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Historial de ventas</h2>
            <a href="{{ route('ventas.create') }}" class="btn btn-primario">+ Nueva venta</a>
        </div>

        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>N° venta</th>
                        <th>Fecha</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ventas as $venta)
                        <tr>
                            <td><strong>{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $venta->detalles_count }} {{ $venta->detalles_count === 1 ? 'producto' : 'productos' }}</td>
                            <td>S/ {{ number_format($venta->total, 2) }}</td>
                            <td>
                                <a href="{{ route('ventas.show', $venta) }}" class="btn btn-secundario btn-sm">Ver detalle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="vacio">Todavía no hay ventas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $ventas->links() }}
    </div>
@endsection