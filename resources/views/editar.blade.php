@extends('layouts.app')

@section('titulo', 'Editar Actividad')

@section('css_extra')
<style>
    .card-custom { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .btn-save { color: white; font-weight: bold; padding: 12px; border-radius: 25px; border: none; width: 100%; transition: 0.3s; }
    .form-section { display: none; margin-top: 20px; }
</style>
@endsection

@section('contenido')
@php
    $tipo_actual = strtolower($entreno['tipo']); 
    if($entreno['tipo'] == 'Carrera') $tipo_actual = 'carrera';
    if($entreno['tipo'] == 'Caminata') $tipo_actual = 'caminata';
    if($entreno['tipo'] == 'Fuerza') $tipo_actual = 'fuerza';
@endphp

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom p-4 bg-white">
                <h3 id="formTitle" class="fw-bold mb-4 text-center text-primary">Editar Actividad</h3>
                
                <form action="/entrenamientos/{{ $entreno['id'] }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha</label>
                            <input type="date" class="form-control @error('fecha') is-invalid @enderror" name="fecha" value="{{ $entreno['fecha'] }}" required>
                            @error('fecha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo</label>
                            <select class="form-select fw-bold @error('modulo') is-invalid @enderror" id="mainCat" name="modulo" required onchange="toggleModule()">
                                <option value="fuerza" {{ $tipo_actual == 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                                <option value="carrera" {{ $tipo_actual == 'carrera' ? 'selected' : '' }}>Carrera</option>
                                <option value="caminata" {{ $tipo_actual == 'caminata' ? 'selected' : '' }}>Caminata</option>
                            </select>
                            @error('modulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="sec-cardio" class="form-section">
                        <h5 class="mb-3"><i class="fas fa-running me-2"></i>Datos Cardio</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold">Distancia (km)</label>
                                <input type="number" step="0.01" class="form-control" name="distancia" value="" placeholder="Opcional">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold">Tiempo (min)</label>
                                <input type="number" class="form-control" name="tiempo" value="{{ $entreno['duracion_minutos'] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div id="sec-fuerza" class="form-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-primary mb-0"><i class="fas fa-dumbbell me-2"></i>Detalle Musculación</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="addExerciseRow()"><i class="fas fa-plus"></i> Añadir Ejercicio</button>
                        </div>
                        
                        <div id="exercises-container">
                            @if(isset($entreno['detalles']) && count($entreno['detalles']) > 0)
                                @foreach($entreno['detalles'] as $index => $detalle)
                                    <div class="exercise-row p-3 mb-3 bg-light rounded border position-relative">
                                        @if($index > 0)
                                            <button type="button" class="btn-close btn-remove-row position-absolute top-0 end-0 m-2" onclick="removeExerciseRow(this)"></button>
                                        @endif
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="small fw-bold">Grupo Muscular</label>
                                                <select class="form-select group-select" name="grupo_muscular[]" onchange="loadEx(this)">
                                                    <option value="">Seleccione...</option>
                                                    <option value="pecho" {{ ($detalle['ejercicio']['grupo_muscular'] ?? '') == 'pecho' ? 'selected' : '' }}>Pecho</option>
                                                    <option value="espalda" {{ ($detalle['ejercicio']['grupo_muscular'] ?? '') == 'espalda' ? 'selected' : '' }}>Espalda</option>
                                                    <option value="pierna" {{ ($detalle['ejercicio']['grupo_muscular'] ?? '') == 'pierna' ? 'selected' : '' }}>Pierna</option>
                                                    <option value="hombro" {{ ($detalle['ejercicio']['grupo_muscular'] ?? '') == 'hombro' ? 'selected' : '' }}>Hombro</option>
                                                    <option value="brazo" {{ ($detalle['ejercicio']['grupo_muscular'] ?? '') == 'brazo' ? 'selected' : '' }}>Brazos (Bíceps/Tríceps)</option>
                                                    <option value="core" {{ ($detalle['ejercicio']['grupo_muscular'] ?? '') == 'core' ? 'selected' : '' }}>Core / Abdominales</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small fw-bold">Ejercicio</label>
                                                <select class="form-select ex-select" name="ejercicio[]">
                                                    <!-- Se rellena con el valor precargado -->
                                                    <option value="{{ $detalle['ejercicio']['nombre'] ?? '' }}" selected>{{ $detalle['ejercicio']['nombre'] ?? 'Seleccione grupo primero...' }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-4"><label class="small fw-bold">Nº Series</label><input type="number" name="series[]" class="form-control" placeholder="Ej: 4" value="{{ $detalle['series'] }}"></div>
                                            <div class="col-4"><label class="small fw-bold">Repeticiones</label><input type="number" name="reps[]" class="form-control" placeholder="Ej: 12" value="{{ $detalle['repeticiones'] }}"></div>
                                            <div class="col-4"><label class="small fw-bold">Carga (Kg)</label><input type="number" name="carga[]" step="0.5" class="form-control" placeholder="Ej: 60" value="{{ $detalle['carga_kg'] }}"></div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Fila de ejercicio base si no hay detalles -->
                                <div class="exercise-row p-3 mb-3 bg-light rounded border position-relative">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Grupo Muscular</label>
                                            <select class="form-select group-select" name="grupo_muscular[]" onchange="loadEx(this)">
                                                <option value="">Seleccione...</option>
                                                <option value="pecho">Pecho</option>
                                                <option value="espalda">Espalda</option>
                                                <option value="pierna">Pierna</option>
                                                <option value="hombro">Hombro</option>
                                                <option value="brazo">Brazos (Bíceps/Tríceps)</option>
                                                <option value="core">Core / Abdominales</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Ejercicio</label>
                                            <select class="form-select ex-select" name="ejercicio[]">
                                                <option>Seleccione grupo primero...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-4"><label class="small fw-bold">Nº Series</label><input type="number" name="series[]" class="form-control" placeholder="Ej: 4"></div>
                                        <div class="col-4"><label class="small fw-bold">Repeticiones</label><input type="number" name="reps[]" class="form-control" placeholder="Ej: 12"></div>
                                        <div class="col-4"><label class="small fw-bold">Carga (Kg)</label><input type="number" name="carga[]" step="0.5" class="form-control" placeholder="Ej: 60"></div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <label class="small fw-bold mt-2">Duración Total (min)</label>
                        <input type="number" class="form-control" name="tiempo_fuerza" value="{{ $entreno['duracion_minutos'] }}">
                    </div>

                    <div class="mt-4">
                        <label class="form-label fw-bold">Sensación (1-10)</label>
                        <input type="range" class="form-range" min="1" max="10" name="sensacion" value="{{ $entreno['sensacion'] ?? 5 }}" oninput="document.getElementById('feelVal').innerText=this.value">
                        <div class="text-center fw-bold fs-5 text-primary" id="feelVal">{{ $entreno['sensacion'] ?? 5 }}</div>
                    </div>

                    <div class="d-grid gap-2 mt-5">
                        <button id="submitBtn" type="submit" class="btn btn-primary btn-save shadow">Guardar Cambios</button>
                        <a href="/" class="btn btn-link text-muted text-decoration-none text-center">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts_extra')
<script>
    function toggleModule() {
        const mainCat = document.getElementById('mainCat');
        const val = mainCat.value;
        const formTitle = document.getElementById('formTitle');
        const submitBtn = document.getElementById('submitBtn');
        const feelVal = document.getElementById('feelVal');

        [mainCat, formTitle, submitBtn, feelVal].forEach(el => {
            if(el) {
                el.classList.remove('border-warning', 'text-warning', 'bg-warning', 'btn-warning',
                                    'border-primary', 'text-primary', 'bg-primary', 'btn-primary',
                                    'border-danger', 'text-danger', 'bg-danger', 'btn-danger',
                                    'border-success', 'text-success', 'bg-success', 'btn-success');
            }
        });

        if (val === 'caminata') {
            mainCat.classList.add('bg-primary', 'text-white');
            if(formTitle) formTitle.classList.add('text-primary');
            if(submitBtn) submitBtn.classList.add('btn-primary');
            if(feelVal) feelVal.classList.add('text-primary');
        } else if (val === 'fuerza') {
            mainCat.classList.add('bg-danger', 'text-white');
            if(formTitle) formTitle.classList.add('text-danger');
            if(submitBtn) submitBtn.classList.add('btn-danger');
            if(feelVal) feelVal.classList.add('text-danger');
        } else if (val === 'carrera') {
            mainCat.classList.add('bg-success', 'text-white');
            if(formTitle) formTitle.classList.add('text-success');
            if(submitBtn) submitBtn.classList.add('btn-success');
            if(feelVal) feelVal.classList.add('text-success');
        } else {
            mainCat.classList.add('bg-primary', 'text-white');
            if(formTitle) formTitle.classList.add('text-primary');
            if(submitBtn) submitBtn.classList.add('btn-primary');
            if(feelVal) feelVal.classList.add('text-primary');
        }

        document.getElementById('sec-fuerza').style.display = val === 'fuerza' ? 'block' : 'none';
        document.getElementById('sec-cardio').style.display = (val === 'carrera' || val === 'caminata') ? 'block' : 'none';
        
        // Evitamos que se envíen dos variables "tiempo" al backend desactivando la que no se usa
        if(val === 'fuerza') {
            document.getElementsByName('tiempo')[0].disabled = true;
            document.getElementsByName('tiempo_fuerza')[0].disabled = false;
            document.getElementsByName('tiempo_fuerza')[0].name = 'tiempo';
        } else {
            const inputsTiempo = document.getElementsByName('tiempo');
            if(inputsTiempo.length > 1) {
                inputsTiempo[1].name = 'tiempo_fuerza';
                inputsTiempo[1].disabled = true;
            }
            inputsTiempo[0].disabled = false;
        }
    }
    
    const exercises = @json($ejercicios->mapWithKeys(function($items, $key) {
        return [strtolower($key) => $items->pluck('nombre')];
    }));

    function loadEx(selectElement) {
        const g = selectElement.value;
        const l = selectElement.closest('.exercise-row').querySelector('.ex-select');
        
        l.innerHTML = '<option value="">Seleccione...</option>'; 
        if(exercises[g]) {
            exercises[g].forEach(e => l.innerHTML += `<option value="${e}">${e}</option>`);
        }
    }

    function addExerciseRow() {
        const container = document.getElementById('exercises-container');
        const firstRow = container.querySelector('.exercise-row');
        const newRow = firstRow.cloneNode(true);
        
        newRow.querySelectorAll('input, select').forEach(input => {
            if(input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
        });

        const exSelect = newRow.querySelector('.ex-select');
        exSelect.innerHTML = '<option>Seleccione grupo primero...</option>';

        if(!newRow.querySelector('.btn-remove-row')) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn-close btn-remove-row position-absolute top-0 end-0 m-2';
            btn.onclick = function() { removeExerciseRow(this); };
            newRow.appendChild(btn);
        }

        container.appendChild(newRow);
    }

    function removeExerciseRow(btn) {
        const container = document.getElementById('exercises-container');
        if(container.querySelectorAll('.exercise-row').length > 1) {
            btn.closest('.exercise-row').remove();
        } else {
            alert('Debes incluir al menos un ejercicio de fuerza.');
        }
    }

    window.onload = toggleModule;
</script>
@endsection