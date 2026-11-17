<div class="form-grid">
    <div class="form-grupo">
        <label for="customer_id">Propietario *</label>
        <select id="customer_id" name="customer_id" required>
            <option value="">Selecciona un cliente</option>
            @foreach ($clientes as $cliente)
                <option value="{{ $cliente->id }}"
                    @selected(old('customer_id', $mascota->customer_id ?? $clienteSeleccionado ?? '') == $cliente->id)>
                    {{ $cliente->first_name }}
                </option>
            @endforeach
        </select>
        @error('customer_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-grupo">
        <label for="name">Nombre de la mascota *</label>
        <input type="text" id="name" name="name"
               value="{{ old('name', $mascota->name ?? '') }}" required maxlength="255">
        @error('name') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-grupo">
        <label for="species">Especie *</label>
        <select id="species" name="species" required>
            <option value="">Selecciona una especie</option>
            @foreach (\App\Models\Pet::ESPECIES as $especie)
                <option value="{{ $especie }}"
                    @selected(old('species', $mascota->species ?? '') === $especie)>
                    {{ $especie }}
                </option>
            @endforeach
        </select>
        @error('species') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-grupo">
        <label for="breed">Raza</label>
        <input type="text" id="breed" name="breed"
               value="{{ old('breed', $mascota->breed ?? '') }}" maxlength="100">
        @error('breed') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-pie">
    <button type="submit" class="btn btn-primario">Guardar</button>
    <a href="{{ route('mascotas.index') }}" class="btn btn-secundario">Cancelar</a>
</div>