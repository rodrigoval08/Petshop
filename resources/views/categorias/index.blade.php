@extends('layouts.app')

@section('titulo', 'Categorías')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Listado de categorías</h2>
            <a href="{{ route('categorias.create') }}" class="btn btn-primario">+ Nueva categoría</a>
        </div>

        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Productos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->id }}</td>
                            <td><strong>{{ $categoria->name }}</strong></td>
                            <td>{{ $categoria->description ?? '—' }}</td>
                            <td>{{ $categoria->productos_count }}</td>
                            <td>
                                <div class="acciones">
                                    <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-secundario btn-sm">Editar</a>

                                    <form method="POST" action="{{ route('categorias.destroy', $categoria) }}"
                                          onsubmit="return confirm('¿Eliminar la categoría {{ $categoria->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-peligro btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="vacio">No hay categorías registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $categorias->links() }}
    </div>
@endsection