@extends('layouts.app')

@section('titulo', 'Nuevo cliente')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Registrar cliente y su mascota</h2>
        </div>

        <form method="POST" action="{{ route('clientes.store') }}">
            @csrf

            <h3 style="margin-bottom:12px;">Datos del propietario</h3>
            @include('clientes._form')

            <h3 style="margin:24px 0 12px;">Mascota <small style="font-weight:400; color:#6b7280;">(opcional, puedes agregarla después)</small></h3>
            <div class="form-grid">
                <div class="form-grupo">
                    <label for="mascota_nombre">Nombre de la mascota</label>
                    <input type="text" id="mascota_nombre" name="mascota_nombre"
                           value="{{ old('mascota_nombre') }}" maxlength="255">
                    @error('mascota_nombre') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-grupo">
                    <label for="mascota_especie">Especie</label>
                    <select id="mascota_especie" name="mascota_especie">
                        <option value="">Selecciona una especie</option>
                        @foreach (\App\Models\Pet::ESPECIES as $especie)
                            <option value="{{ $especie }}" @selected(old('mascota_especie') === $especie)>{{ $especie }}</option>
                        @endforeach
                    </select>
                    @error('mascota_especie') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-grupo">
                    <label for="mascota_raza">Raza</label>
                    <input type="text" id="mascota_raza" name="mascota_raza"
                           value="{{ old('mascota_raza') }}" maxlength="100">
                    @error('mascota_raza') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-pie">
                <button type="submit" class="btn btn-primario">Guardar</button>
                <a href="{{ route('clientes.index') }}" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </div>
@endsection