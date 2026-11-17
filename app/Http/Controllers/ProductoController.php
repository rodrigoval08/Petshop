<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function index(Request $request): View
    {
        // RF05: búsqueda por nombre/SKU + filtro por categoría
        $productos = Producto::with('categoria')
            ->buscar($request->query('buscar'))
            ->deCategoria($request->query('categoria'))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $categorias = Categoria::orderBy('name')->get();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create(): View
    {
        $categorias = Categoria::orderBy('name')->get();

        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
        'category_id' => ['required', 'exists:categories, id'],
        'sku'         => ['required', 'string', 'max:50', 'unique:products,sku'],
        'name'        => ['required', 'string', 'max:255'],
        'price'       => ['required', 'numeric', 'min:0'],
        'stock'       => ['required', 'integer', 'min:0'],
        'image'       => ['nullable', 'url', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ],$this->reglas(), $this->mensajes());

        if ($request->hasFile('imagen')) {
            $datos['image_path'] = $request->file('imagen')->store('productos', 'public');
        }

        unset($datos['imagen'], $datos['quitar_imagen']);

        Producto::create($datos);

        return redirect()
            ->route('productos.index')
            ->with('exito', 'Producto registrado correctamente.');
    }

    public function edit(Producto $producto): View
    {
        $categorias = Categoria::orderBy('name')->get();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $datos = $request->validate($this->reglas($producto->id), $this->mensajes());

        if ($request->hasFile('imagen')) {
            // Reemplaza: borra la imagen anterior para no acumular archivos
            $this->borrarImagen($producto);
            $datos['image_path'] = $request->file('imagen')->store('productos', 'public');
        } elseif ($request->boolean('quitar_imagen')) {
            $this->borrarImagen($producto);
            $datos['image_path'] = null;
        }

        unset($datos['imagen'], $datos['quitar_imagen']);

        $producto->update($datos);

        return redirect()
            ->route('productos.index')
            ->with('exito', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        // No se elimina un producto con ventas registradas (historial auditable)
        if ($producto->ventas()->exists()) {
            return redirect()
                ->route('productos.index')
                ->with('error', "No se puede eliminar «{$producto->name}» porque tiene ventas registradas.");
        }

        try {
            $producto->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('productos.index')
                ->with('error', 'La base de datos rechazó la eliminación por registros relacionados.');
        }

        // Ya se eliminó el registro: ahora sí se borra su imagen
        $this->borrarImagen($producto);

        return redirect()
            ->route('productos.index')
            ->with('exito', 'Producto eliminado correctamente.');
    }

    private function borrarImagen(Producto $producto): void
    {
        if ($producto->image_path) {
            Storage::disk('public')->delete($producto->image_path);
        }
    }

    private function reglas(?int $ignorarId = null): array
    {
        return [
            'category_id'   => ['required', 'exists:categories,id'],
            'sku'           => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($ignorarId)],
            'name'          => ['required', 'string', 'max:255'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'imagen'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'quitar_imagen' => ['nullable', 'boolean'],
        ];
    }

    private function mensajes(): array
    {
        return [
            'category_id.required' => 'Selecciona una categoría.',
            'category_id.exists'   => 'La categoría seleccionada no existe.',
            'sku.required'         => 'El SKU / código de barra es obligatorio.',
            'sku.unique'           => 'Ya existe un producto con ese SKU.',
            'name.required'        => 'El nombre del producto es obligatorio.',
            'price.required'       => 'El precio es obligatorio.',
            'price.numeric'        => 'El precio debe ser un número.',
            'price.min'            => 'El precio no puede ser negativo.',
            'stock.required'       => 'El stock es obligatorio.',
            'stock.integer'        => 'El stock debe ser un número entero.',
            'stock.min'            => 'El stock no puede ser negativo.',
            'imagen.image'         => 'El archivo debe ser una imagen.',
            'imagen.mimes'         => 'La imagen debe ser JPG, PNG o WEBP.',
            'imagen.max'           => 'La imagen no puede pesar más de 2 MB.',
            'imagen.uploaded'      => 'No se pudo subir la imagen (revisa que pese menos de 2 MB).',
        ];
    }
}