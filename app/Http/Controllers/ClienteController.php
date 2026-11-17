<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Pet;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = $request->query('buscar');

        $clientes = Customer::with('mascotas')
            ->when($buscar, function ($q) use ($buscar) {
                $q->where(function ($q2) use ($buscar) {
                    $q2->where('first_name', 'like', "%{$buscar}%")
                       ->orWhere('phone', 'like', "%{$buscar}%")
                       ->orWhere('email', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('first_name')
            ->paginate(10)
            ->withQueryString();

        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'first_name'      => ['required', 'string', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:20'],
            'email'           => ['nullable', 'email', 'max:255'],
            // Mascota inicial (opcional): si pones el nombre, la especie es obligatoria
            'mascota_nombre'  => ['nullable', 'string', 'max:255'],
            'mascota_especie' => ['required_with:mascota_nombre', 'nullable', Rule::in(Pet::ESPECIES)],
            'mascota_raza'    => ['nullable', 'string', 'max:100'],
        ], $this->mensajes());

        // Cliente y mascota se guardan juntos o no se guarda nada
        DB::transaction(function () use ($datos) {
            $cliente = Customer::create([
                'first_name' => $datos['first_name'],
                'phone'      => $datos['phone'] ?? null,
                'email'      => $datos['email'] ?? null,
            ]);

            if (!empty($datos['mascota_nombre'])) {
                $cliente->mascotas()->create([
                    'name'    => $datos['mascota_nombre'],
                    'species' => $datos['mascota_especie'],
                    'breed'   => $datos['mascota_raza'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('clientes.index')
            ->with('exito', 'Cliente registrado correctamente.');
    }

    public function edit(Customer $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Customer $cliente): RedirectResponse
    {
        $datos = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'email'      => ['nullable', 'email', 'max:255'],
        ], $this->mensajes());

        $cliente->update($datos);

        return redirect()
            ->route('clientes.index')
            ->with('exito', 'Cliente actualizado correctamente.');
    }

    public function destroy(Customer $cliente): RedirectResponse
    {
        // No se elimina un cliente que todavía tiene mascotas
        if ($cliente->mascotas()->exists()) {
            return redirect()
                ->route('clientes.index')
                ->with('error', "No se puede eliminar a «{$cliente->first_name}» porque tiene mascotas registradas. Elimina primero sus mascotas.");
        }

        try {
            $cliente->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('clientes.index')
                ->with('error', 'La base de datos rechazó la eliminación por registros relacionados.');
        }

        return redirect()
            ->route('clientes.index')
            ->with('exito', 'Cliente eliminado correctamente.');
    }

    private function mensajes(): array
    {
        return [
            'first_name.required'           => 'El nombre del cliente es obligatorio.',
            'email.email'                   => 'Ingresa un correo válido.',
            'phone.max'                     => 'El teléfono no puede superar los 20 caracteres.',
            'mascota_especie.required_with' => 'Indica la especie de la mascota.',
            'mascota_especie.in'            => 'Selecciona una especie válida.',
        ];
    }
}