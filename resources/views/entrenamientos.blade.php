@extends('layouts.app')

@section('titulo', 'Historial de Entrenamientos')

@section('css_extra')
<style>
    .post-card { border: none; border-radius: 15px; background: white; }
    .filter-btn { border-radius: 20px; font-weight: 600; font-size: 0.85rem; }
    .filter-btn.active { box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
</style>
@endsection

@section('contenido')
<div class="container px-4 mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" style="color: var(--primary-color)"><i class="fas fa-dumbbell me-2 text-primary"></i>Historial de Entrenamientos</h3>
        <a href="/registro" class="btn btn-primary rounded-pill fw-bold bg-primary shadow-sm"><i class="fas fa-plus me-2"></i>Nueva Actividad</a>
    </div>

    {{-- #4: Filtros por tipo --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="/historial" class="btn btn-sm filter-btn {{ !request('tipo') ? 'btn-primary active' : 'btn-outline-secondary' }}">
            <i class="fas fa-list me-1"></i>Todos
        </a>
        <a href="/historial?tipo=Fuerza" class="btn btn-sm filter-btn {{ request('tipo') === 'Fuerza' ? 'btn-danger active' : 'btn-outline-danger' }}">
            <i class="fas fa-dumbbell me-1"></i>Fuerza
        </a>
        <a href="/historial?tipo=Carrera" class="btn btn-sm filter-btn {{ request('tipo') === 'Carrera' ? 'btn-success active' : 'btn-outline-success' }}">
            <i class="fas fa-running me-1"></i>Carrera
        </a>
        <a href="/historial?tipo=Caminata" class="btn btn-sm filter-btn {{ request('tipo') === 'Caminata' ? 'btn-primary active' : 'btn-outline-primary' }}">
            <i class="fas fa-walking me-1"></i>Caminata
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
            @forelse ($actividades as $actividad)
                @include('partials.actividad', ['actividad' => $actividad])
            @empty
                <div class="alert alert-info text-center py-4 rounded-4" style="border: 2px dashed #0d6efd; background-color: #f8faff;">
                    <i class="fas fa-running fa-3x mb-3 text-secondary"></i>
                    <h5>Todavía no hay historial registrado{{ request('tipo') ? ' para ' . request('tipo') : '' }}.</h5>
                    <p class="text-muted">Desempolva tus zapatillas y registra tu primera actividad hoy mismo.</p>
                </div>
            @endforelse

            {{-- #19: Paginación con texto en español --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $actividades->appends(['tipo' => request('tipo')])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- #28: Botón exportar CSV --}}
<div class="text-center mb-5">
    <a href="/historial/exportar" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-file-csv me-2"></i>Descargar historial (CSV)
    </a>
</div>
@endsection
