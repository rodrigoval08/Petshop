@extends('layouts.app')

@section('titulo', 'Nueva mascota')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Registrar mascota</h2>
        </div>

        <form method="POST" action="{{ route('mascotas.store') }}">
            @csrf
            @include('mascotas._form')
        </form>
    </div>
@endsection