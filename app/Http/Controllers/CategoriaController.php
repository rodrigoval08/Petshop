<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::withCount('productos')
            ->orderBy('name')
            ->paginate(10);

        return view('categorias.index', compact('categorias'));
    }

    public function create(): View
    {
        return view('categorias.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ], $this->mensajes());

        Categoria::create($datos);

        return redirect()
            ->route('categorias.index')
            ->with('exito', 'Categoría registrada correctamente.');
    }

    public function edit(Categoria $categoria): View
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $datos = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($categoria->id)],
            'description' => ['nullable', 'string', 'max:500'],
        ], $this->mensajes());

        $categoria->update($datos);

        return redirect()
            ->route('categorias.index')
            ->with('exito', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        // RF02: bloquear si tiene productos asociados
        if ($categoria->productos()->exists()) {
            return redirect()
                ->route('categorias.index')
                ->with('error', "No se puede eliminar «{$categoria->name}» porque tiene productos asociados.");
        }

        try {
            $categoria->delete();
        } catch (QueryException $e) {
            // Segunda barrera: restrictOnDelete() de la base de datos
            return redirect()
                ->route('categorias.index')
                ->with('error', 'La base de datos rechazó la eliminación por registros relacionados.');
        }

        return redirect()
            ->route('categorias.index')
            ->with('exito', 'Categoría eliminada correctamente.');
    }

    private function mensajes(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.unique'   => 'Ya existe una categoría con ese nombre.',
            'name.max'      => 'El nombre no puede superar los 255 caracteres.',
            'description.max' => 'La descripción no puede superar los 500 caracteres.',
        ];
    }
}