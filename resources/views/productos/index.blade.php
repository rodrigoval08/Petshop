@extends('layouts.app')

@section('titulo', 'Productos')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Catálogo de productos</h2>
            <a href="{{ route('productos.create') }}" class="btn btn-primario">+ Nuevo producto</a>
        </div>

        <form method="GET" action="{{ route('productos.index') }}" class="barra-filtros">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre o SKU...">

            <select name="categoria">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(request('categoria') == $categoria->id)>
                        {{ $categoria->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primario">Filtrar</button>
            <a href="{{ route('productos.index') }}" class="btn btn-secundario">Limpiar</a>
        </form>

        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>SKU</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $producto)
                        <tr>
                            <td>
                                @if ($producto->imagenUrl())
                                    <img src="{{ $producto->imagenUrl() }}" alt="{{ $producto->name }}" loading="lazy"
                                         style="width:48px; height:48px; object-fit:cover; border-radius:8px; border:1px solid #e5e7eb;">
                                @else
                                    <div style="width:48px; height:48px; border-radius:8px; background:#eef2ff; display:flex; align-items:center; justify-content:center; font-size:20px;">🐾</div>
                                @endif
                            </td>
                            <td>{{ $producto->sku }}</td>
                            <td><strong>{{ $producto->name }}</strong></td>
                            <td>{{ $producto->categoria->name }}</td>
                            <td>S/ {{ number_format($producto->price, 2) }}</td>
                            <td>{{ $producto->stock }}</td>
                            <td>@include('productos._badge', ['stock' => $producto->stock])</td>
                            <td>
                                <div class="acciones">
                                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-secundario btn-sm">Editar</a>

                                    <form method="POST" action="{{ route('productos.destroy', $producto) }}"
                                          onsubmit="return confirm('¿Eliminar el producto {{ $producto->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-peligro btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="vacio">No se encontraron productos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $productos->links() }}
    </div>
@endsection