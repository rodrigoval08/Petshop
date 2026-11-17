<div class="form-grid">
    <div class="form-grupo">
        <label for="name">Nombre *</label>
        <input type="text" id="name" name="name"
               value="{{ old('name', $categoria->name ?? '') }}" required maxlength="255">
        @error('name') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-grupo">
        <label for="description">Descripción</label>
        <textarea id="description" name="description" rows="3" maxlength="500">{{ old('description', $categoria->description ?? '') }}</textarea>
        @error('description') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-pie">
    <button type="submit" class="btn btn-primario">Guardar</button>
    <a href="{{ route('categorias.index') }}" class="btn btn-secundario">Cancelar</a>
</div>