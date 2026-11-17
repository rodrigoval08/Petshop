@extends('layouts.app')

@section('titulo', 'Editar cliente')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Editar cliente</h2>
        </div>

        <form method="POST" action="{{ route('clientes.update', $cliente) }}">
            @csrf
            @method('PUT')

            @include('clientes._form')

            <div class="form-pie">
                <button type="submit" class="btn btn-primario">Guardar cambios</button>
                <a href="{{ route('clientes.index') }}" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </div>
@endsection