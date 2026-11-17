@extends('layouts.app')

@section('titulo', 'Diseño (StarUML)')

@section('contenido')
    <div class="panel">
        <div class="panel-titulo">
            <h2>Repositorio de diseño</h2>
        </div>
        <p style="color:#6b7280; margin-bottom:16px;">
            Sube los diagramas exportados desde StarUML (PNG, máximo 2 MB cada uno) y redacta tus notas de análisis
            para justificar que el diseño coincide con el código.
        </p>

        <form method="POST" action="{{ route('diagramas.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-grupo">
                    <label for="use_case">Diagrama de casos de uso (PNG)</label>
                    <input type="file" id="use_case" name="use_case" accept="image/png">
                    @error('use_case') <div class="error">{{ $message }}</div> @enderror

                    @if ($diagrama?->use_case_path)
                        <a href="{{ asset('storage/' . $diagrama->use_case_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $diagrama->use_case_path) }}" alt="Diagrama de casos de uso"
                                 style="width:100%; margin-top:10px; border:1px solid #e5e7eb; border-radius:8px;">
                        </a>
                        <small style="color:#6b7280;">Clic en la imagen para verla completa. Sube otro PNG para reemplazarla.</small>
                    @else
                        <small style="color:#6b7280;">Todavía no has subido este diagrama.</small>
                    @endif
                </div>

                <div class="form-grupo">
                    <label for="class_diagram">Diagrama de clases (PNG)</label>
                    <input type="file" id="class_diagram" name="class_diagram" accept="image/png">
                    @error('class_diagram') <div class="error">{{ $message }}</div> @enderror

                    @if ($diagrama?->class_diagram_path)
                        <a href="{{ asset('storage/' . $diagrama->class_diagram_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $diagrama->class_diagram_path) }}" alt="Diagrama de clases"
                                 style="width:100%; margin-top:10px; border:1px solid #e5e7eb; border-radius:8px;">
                        </a>
                        <small style="color:#6b7280;">Clic en la imagen para verla completa. Sube otro PNG para reemplazarla.</small>
                    @else
                        <small style="color:#6b7280;">Todavía no has subido este diagrama.</small>
                    @endif
                </div>
            </div>

            <div class="form-grupo" style="margin-top:20px;">
                <label for="student_notes">Notas de análisis</label>
                <textarea id="student_notes" name="student_notes" rows="8" maxlength="5000"
                          placeholder="Ej.: La relación 1 a N entre Customer y Pet se implementó con customer_id (foreignId + restrictOnDelete) en la migración de pets...">{{ old('student_notes', $diagrama->student_notes ?? '') }}</textarea>
                @error('student_notes') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-pie">
                <button type="submit" class="btn btn-primario">Guardar</button>
            </div>
        </form>
    </div>
@endsection