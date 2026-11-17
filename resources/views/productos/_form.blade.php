<div class="form-grid">
    <div class="form-grupo">
        <label for="sku">SKU / Código de barra *</label>
        <input type="text" id="sku" name="sku"
               value="{{ old('sku', $producto->sku ?? '') }}" required maxlength="50">
        @error('sku') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-grupo">
        <label for="name">Nombre *</label>
        <input type="text" id="name" name="name"
               value="{{ old('name', $producto->name ?? '') }}" required maxlength="255">
        @error('name') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-grupo">
        <label for="category_id">Categoría *</label>
        <select id="category_id" name="category_id" required>
            <option value="">Selecciona una categoría</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}"
                    @selected(old('category_id', $producto->category_id ?? '') == $categoria->id)>
                    {{ $categoria->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-grupo">
        <label for="price">Precio (S/) *</label>
        <input type="number" id="price" name="price" step="0.01" min="0"
               value="{{ old('price', $producto->price ?? '') }}" required>
        @error('price') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-grupo">
        <label for="stock">Stock *</label>
        <input type="number" id="stock" name="stock" step="1" min="0"
               value="{{ old('stock', $producto->stock ?? 0) }}" required>
        @error('stock') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-grupo">
        <label for="imagen">Imagen del producto</label>
        <input type="file" id="imagen" name="imagen" accept="image/png,image/jpeg,image/webp">
        <small style="color:#6b7280;">JPG, PNG o WEBP, máximo 2 MB.</small>
        @error('imagen') <div class="error">{{ $message }}</div> @enderror

        @if (isset($producto) && $producto->imagenUrl())
            <img src="{{ $producto->imagenUrl() }}" alt="{{ $producto->name }}"
                 style="display:block; width:120px; height:120px; object-fit:cover; margin-top:10px; border:1px solid #e5e7eb; border-radius:8px;">

            <label style="display:flex; align-items:center; gap:8px; margin-top:8px; font-weight:400;">
                <input type="checkbox" name="quitar_imagen" value="1" style="width:auto;">
                Quitar la imagen actual
            </label>
        @endif
    </div>
</div>

<div class="form-pie">
    <button type="submit" class="btn btn-primario">Guardar</button>
    <a href="{{ route('productos.index') }}" class="btn btn-secundario">Cancelar</a>
</div>