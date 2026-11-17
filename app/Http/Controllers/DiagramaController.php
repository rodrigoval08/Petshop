<?php

namespace App\Http\Controllers;

use App\Models\SetupDiagram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DiagramaController extends Controller
{
    public function index(): View
    {
        $diagrama = SetupDiagram::first();

        return view('diagramas.index', compact('diagrama'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'use_case'      => ['nullable', 'file', 'mimes:png', 'max:2048'],
            'class_diagram' => ['nullable', 'file', 'mimes:png', 'max:2048'],
            'student_notes' => ['nullable', 'string', 'max:5000'],
        ], [
            'use_case.mimes'      => 'El diagrama de casos de uso debe ser un archivo PNG.',
            'use_case.max'        => 'El diagrama de casos de uso no puede pesar más de 2 MB.',
            'use_case.uploaded'   => 'No se pudo subir el diagrama de casos de uso (revisa que pese menos de 2 MB).',
            'class_diagram.mimes' => 'El diagrama de clases debe ser un archivo PNG.',
            'class_diagram.max'   => 'El diagrama de clases no puede pesar más de 2 MB.',
            'class_diagram.uploaded' => 'No se pudo subir el diagrama de clases (revisa que pese menos de 2 MB).',
            'student_notes.max'   => 'Las notas no pueden superar los 5000 caracteres.',
        ]);

        $diagrama = SetupDiagram::firstOrNew();

        $archivos = [
            'use_case'      => 'use_case_path',
            'class_diagram' => 'class_diagram_path',
        ];

        foreach ($archivos as $campo => $columna) {
            if ($request->hasFile($campo)) {
                // Borra el archivo anterior para no acumular imágenes
                if ($diagrama->$columna) {
                    Storage::disk('public')->delete($diagrama->$columna);
                }

                $diagrama->$columna = $request->file($campo)->store('diagramas', 'public');
            }
        }

        $diagrama->student_notes = $datos['student_notes'] ?? null;
        $diagrama->save();

        return redirect()
            ->route('diagramas.index')
            ->with('exito', 'Repositorio de diseño actualizado correctamente.');
    }
}