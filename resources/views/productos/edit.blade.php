@extends('layouts.app')

@section('titulo', 'Editar producto')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Editar producto</h2>
        </div>

        <form method="POST" action="{{ route('productos.update', $producto) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('productos._form')
        </form>
    </div>
@endsection