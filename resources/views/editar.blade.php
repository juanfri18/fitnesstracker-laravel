@extends('layouts.app')

@section('titulo', 'Editar Actividad')

@section('css_extra')
<style>
    .card-custom { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .btn-save { background-color: #ffc107; color: #000; font-weight: bold; border: none; width: 100%; padding: 12px; border-radius: 25px;} 
    .btn-save:hover { background-color: #e0a800; }
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
                <h3 class="fw-bold mb-4 text-center text-warning">Editar Actividad</h3>
                
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
                            <select class="form-select border-warning fw-bold" id="mainCat" name="modulo" required onchange="toggleModule()">
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
                        <input type="range" class="form-range" min="1" max="10" name="sensacion" value="{{ $entreno['sensacion'] ?? 5 }}" oninput="document.getElementById('val').innerText=this.value">
                        <div class="text-center fw-bold fs-5 text-warning" id="val">{{ $entreno['sensacion'] ?? 5 }}</div>
                    </div>

                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-save shadow">Guardar Cambios</button>
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
        const val = document.getElementById('mainCat').value;
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