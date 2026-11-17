@extends('layouts.app')

@section('titulo', 'Mascotas')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Listado de mascotas</h2>
            <a href="{{ route('mascotas.create') }}" class="btn btn-primario">+ Nueva mascota</a>
        </div>

        <form method="GET" action="{{ route('mascotas.index') }}" class="barra-filtros">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por mascota, raza o propietario...">
            <button type="submit" class="btn btn-primario">Buscar</button>
            <a href="{{ route('mascotas.index') }}" class="btn btn-secundario">Limpiar</a>
        </form>

        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Especie</th>
                        <th>Raza</th>
                        <th>Propietario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mascotas as $mascota)
                        <tr>
                            <td><strong>{{ $mascota->name }}</strong></td>
                            <td>{{ $mascota->species }}</td>
                            <td>{{ $mascota->breed ?? '—' }}</td>
                            <td>{{ $mascota->cliente->first_name }}</td>
                            <td>
                                <div class="acciones">
                                    <a href="{{ route('mascotas.edit', $mascota) }}" class="btn btn-secundario btn-sm">Editar</a>

                                    <form method="POST" action="{{ route('mascotas.destroy', $mascota) }}"
                                          onsubmit="return confirm('¿Eliminar a {{ $mascota->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-peligro btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="vacio">No hay mascotas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $mascotas->links() }}
    </div>
@endsection