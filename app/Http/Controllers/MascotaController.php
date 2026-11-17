<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MascotaController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = $request->query('buscar');

        $mascotas = Pet::with('cliente')
            ->when($buscar, function ($q) use ($buscar) {
                $q->where(function ($q2) use ($buscar) {
                    $q2->where('name', 'like', "%{$buscar}%")
                       ->orWhere('breed', 'like', "%{$buscar}%")
                       ->orWhereHas('cliente', fn ($c) => $c->where('first_name', 'like', "%{$buscar}%"));
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('mascotas.index', compact('mascotas'));
    }

    public function create(Request $request): View
    {
        $clientes = Customer::orderBy('first_name')->get();
        $clienteSeleccionado = $request->query('cliente');

        return view('mascotas.create', compact('clientes', 'clienteSeleccionado'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate($this->reglas(), $this->mensajes());

        Pet::create($datos);

        return redirect()
            ->route('mascotas.index')
            ->with('exito', 'Mascota registrada correctamente.');
    }

    public function edit(Pet $mascota): View
    {
        $clientes = Customer::orderBy('first_name')->get();

        return view('mascotas.edit', compact('mascota', 'clientes'));
    }

    public function update(Request $request, Pet $mascota): RedirectResponse
    {
        $datos = $request->validate($this->reglas(), $this->mensajes());

        $mascota->update($datos);

        return redirect()
            ->route('mascotas.index')
            ->with('exito', 'Mascota actualizada correctamente.');
    }

    public function destroy(Pet $mascota): RedirectResponse
    {
        $mascota->delete();

        return redirect()
            ->route('mascotas.index')
            ->with('exito', 'Mascota eliminada correctamente.');
    }

    private function reglas(): array
    {
        return [
            // Toda mascota pertenece obligatoriamente a un cliente (1 a N)
            'customer_id' => ['required', 'exists:customers,id'],
            'name'        => ['required', 'string', 'max:255'],
            'species'     => ['required', Rule::in(Pet::ESPECIES)],
            'breed'       => ['nullable', 'string', 'max:100'],
        ];
    }

    private function mensajes(): array
    {
        return [
            'customer_id.required' => 'Selecciona el propietario de la mascota.',
            'customer_id.exists'   => 'El cliente seleccionado no existe.',
            'name.required'        => 'El nombre de la mascota es obligatorio.',
            'species.required'     => 'Selecciona la especie.',
            'species.in'           => 'Selecciona una especie válida.',
        ];
    }
}