@extends('layouts.app')

@section('titulo', 'Nueva venta')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Registrar venta</h2>
        </div>

        <div class="barra-filtros">
            <select id="selProducto">
                <option value="">Selecciona un producto</option>
                @foreach ($productos as $producto)
                    <option value="{{ $producto->id }}" @disabled($producto->stock <= 0)>
                        {{ $producto->sku }} — {{ $producto->name }} (stock: {{ $producto->stock }})
                    </option>
                @endforeach
            </select>
            <input type="number" id="inpCantidad" min="1" value="1" style="flex:none; width:110px;">
            <button type="button" class="btn btn-primario" id="btnAgregar">+ Agregar</button>
        </div>

        <form method="POST" action="{{ route('ventas.store') }}" id="formVenta">
            @csrf

            <div class="tabla-wrap">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="lineas"></tbody>
                </table>
            </div>

            <div style="text-align:right; font-size:20px; margin-top:16px;">
                Total: <strong id="total">S/ 0.00</strong>
            </div>

            <div class="form-pie">
                <button type="submit" class="btn btn-primario">Finalizar venta</button>
                <a href="{{ route('ventas.index') }}" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    const catalogo = @json($catalogo);

    // Si la venta falló (ej. sin stock), se reconstruye el carrito con lo que había armado
    let carrito = Object.values(@json(old('items', [])))
        .filter(l => catalogo[l.product_id])
        .map(l => ({ product_id: parseInt(l.product_id), quantity: parseInt(l.quantity) || 1 }));

    const tbody = document.getElementById('lineas');

    function esc(texto) {
        return String(texto).replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function dinero(n) {
        return 'S/ ' + n.toFixed(2);
    }

    function render() {
        let total = 0;

        if (carrito.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="vacio">Agrega productos a la venta.</td></tr>';
        } else {
            tbody.innerHTML = carrito.map((linea, i) => {
                const p = catalogo[linea.product_id];
                const subtotal = p.price * linea.quantity;
                total += subtotal;

                return `
                    <tr>
                        <td>
                            <strong>${esc(p.name)}</strong><br>
                            <small style="color:#6b7280;">SKU ${esc(p.sku)} · stock: ${p.stock}</small>
                            <input type="hidden" name="items[${i}][product_id]" value="${p.id}">
                        </td>
                        <td>${dinero(p.price)}</td>
                        <td>
                            <input type="number" name="items[${i}][quantity]" value="${linea.quantity}"
                                   min="1" max="${p.stock}" data-i="${i}" class="cantidad"
                                   style="width:90px; padding:6px; border:1px solid #d1d5db; border-radius:6px;">
                        </td>
                        <td>${dinero(subtotal)}</td>
                        <td><button type="button" class="btn btn-peligro btn-sm" data-quitar="${i}">Quitar</button></td>
                    </tr>`;
            }).join('');
        }

        document.getElementById('total').textContent = dinero(total);
    }

    document.getElementById('btnAgregar').addEventListener('click', () => {
        const id = parseInt(document.getElementById('selProducto').value);
        const cantidad = parseInt(document.getElementById('inpCantidad').value) || 1;

        if (!id) { alert('Selecciona un producto.'); return; }

        const existente = carrito.find(l => l.product_id === id);
        if (existente) {
            existente.quantity += cantidad;
        } else {
            carrito.push({ product_id: id, quantity: cantidad });
        }

        document.getElementById('selProducto').value = '';
        document.getElementById('inpCantidad').value = 1;
        render();
    });

    tbody.addEventListener('change', (e) => {
        if (e.target.classList.contains('cantidad')) {
            carrito[e.target.dataset.i].quantity = Math.max(1, parseInt(e.target.value) || 1);
            render();
        }
    });

    tbody.addEventListener('click', (e) => {
        if (e.target.dataset.quitar !== undefined) {
            carrito.splice(parseInt(e.target.dataset.quitar), 1);
            render();
        }
    });

    document.getElementById('formVenta').addEventListener('submit', (e) => {
        if (carrito.length === 0) {
            e.preventDefault();
            alert('Agrega al menos un producto a la venta.');
        }
    });

    render();
</script>
@endpush