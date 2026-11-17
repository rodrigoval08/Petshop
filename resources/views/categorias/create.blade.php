@extends('layouts.app')

@section('titulo', 'Nueva categoría')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Registrar categoría</h2>
        </div>

        <form method="POST" action="{{ route('categorias.store') }}">
            @csrf
            @include('categorias._form')
        </form>
    </div>
@endsection