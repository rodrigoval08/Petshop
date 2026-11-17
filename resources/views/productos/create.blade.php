@extends('layouts.app')

@section('titulo', 'Nuevo producto')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Registrar producto</h2>
        </div>

        <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data"> <input type="file" name="image" accept="image/*">
            @csrf
            @include('productos._form')
        </form>
    </div>
@endsection