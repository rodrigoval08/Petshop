@extends('layouts.app')

@section('titulo', 'Editar categoría')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Editar categoría</h2>
        </div>

        <form method="POST" action="{{ route('categorias.update', $categoria) }}">
            @csrf
            @method('PUT')
            @include('categorias._form')
        </form>
    </div>
@endsection