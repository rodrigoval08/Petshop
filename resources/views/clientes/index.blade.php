@extends('layouts.app')

@section('titulo', 'Clientes')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Listado de clientes</h2>
            <a href="{{ route('clientes.create') }}" class="btn btn-primario">+ Nuevo cliente</a>
        </div>

        <form method="GET" action="{{ route('clientes.index') }}" class="barra-filtros">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre, teléfono o correo...">
            <button type="submit" class="btn btn-primario">Buscar</button>
            <a href="{{ route('clientes.index') }}" class="btn btn-secundario">Limpiar</a>
        </form>

        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Mascotas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td><strong>{{ $cliente->first_name }}</strong></td>
                            <td>{{ $cliente->phone ?? '—' }}</td>
                            <td>{{ $cliente->email ?? '—' }}</td>
                            <td>
                                @forelse ($cliente->mascotas as $mascota)
                                    {{ $mascota->name }} ({{ $mascota->species }})@if (!$loop->last), @endif
                                @empty
                                    —
                                @endforelse
                            </td>
                            <td>
                                <div class="acciones">
                                    <a href="{{ route('mascotas.create', ['cliente' => $cliente->id]) }}" class="btn btn-primario btn-sm">+ Mascota</a>
                                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-secundario btn-sm">Editar</a>

                                    <form method="POST" action="{{ route('clientes.destroy', $cliente) }}"
                                          onsubmit="return confirm('¿Eliminar a {{ $cliente->first_name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-peligro btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="vacio">No hay clientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $clientes->links() }}
    </div>
@endsection