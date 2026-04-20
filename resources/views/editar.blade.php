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
                            <input type="date" class="form-control" name="fecha" value="{{ $entreno['fecha'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo</label>
                            <select class="form-select fw-bold" id="mainCat" name="modulo" required onchange="toggleModule()">
                                <option value="fuerza" {{ $tipo_actual == 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                                <option value="carrera" {{ $tipo_actual == 'carrera' ? 'selected' : '' }}>Carrera</option>
                                <option value="caminata" {{ $tipo_actual == 'caminata' ? 'selected' : '' }}>Caminata</option>
                            </select>
                        </div>
                    </div>

                    <div id="sec-cardio" class="form-section">
                        <h5 class="mb-3"><i class="fas fa-running me-2"></i>Datos Cardio</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold">Distancia (km)</label>
                                <input type="number" step="0.01" class="form-control" name="distancia" value="{{ $entreno['distancia_km'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold">Tiempo (min)</label>
                                <input type="number" class="form-control" name="tiempo" value="{{ $entreno['duracion_minutos'] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div id="sec-fuerza" class="form-section">
                        <h5 class="mb-3"><i class="fas fa-dumbbell me-2"></i>Datos Fuerza</h5>
                        <label class="small fw-bold">Duración Total (min)</label>
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
    window.onload = toggleModule;
</script>
@endsection