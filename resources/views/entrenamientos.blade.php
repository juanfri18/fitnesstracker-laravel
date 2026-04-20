@extends('layouts.app')

@section('titulo', 'Historial de Entrenamientos')

@section('css_extra')
<style>
    .post-card { border: none; border-radius: 15px; background: white; }
</style>
@endsection

@section('contenido')
<div class="container px-4 mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" style="color: var(--primary-color)"><i class="fas fa-dumbbell me-2 text-primary"></i>Historial de Entrenamientos</h3>
        <a href="/registro" class="btn btn-primary rounded-pill fw-bold bg-primary shadow-sm"><i class="fas fa-plus me-2"></i>Nueva Actividad</a>
    </div>

    <div class="row">
        <div class="col-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
            @forelse ($actividades as $actividad)
                @include('partials.actividad', ['actividad' => $actividad])
            @empty
                <div class="alert alert-info text-center py-4 rounded-4" style="border: 2px dashed #0d6efd; background-color: #f8faff;">
                    <i class="fas fa-running fa-3x mb-3 text-secondary"></i>
                    <h5>Todavía no hay historial registrado.</h5>
                    <p class="text-muted">Desempolva tus zapatillas y registra tu primera actividad hoy mismo.</p>
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $actividades->links('pagination::bootstrap-5') }} 
            </div>
        </div>
    </div>
</div>
@endsection
