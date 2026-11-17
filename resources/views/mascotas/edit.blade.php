@extends('layouts.app')

@section('titulo', 'Editar mascota')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Editar mascota</h2>
        </div>

        <form method="POST" action="{{ route('mascotas.update', $mascota) }}">
            @csrf
            @method('PUT')
            @include('mascotas._form')
        </form>
    </div>
@endsection