@extends('layouts.app')

@section('titulo', 'Estadísticas')

@section('css_extra')
<style>
    .stat-card { border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
</style>
@endsection

@section('contenido')
<div class="container py-5">
    <h2 class="fw-bold mb-4" style="color: var(--primary-color);">Tus Métricas</h2>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100">
                <small class="text-muted">Distancia Total</small>
                <h3 class="fw-bold text-primary">{{ number_format($totales['total_km'], 1) }} km</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100">
                <small class="text-muted">Esta Semana</small>
                <h3 class="fw-bold text-success">{{ number_format($semana['sem_cal'], 0) }} kcal</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100">
                <small class="text-muted">Tiempo Total</small>
                <h3 class="fw-bold text-info">{{ floor($totales['total_min']/60) }}h {{ $totales['total_min']%60 }}m</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100">
                <small class="text-muted d-block">Mejor Distancia</small>
                <h3 class="fw-bold text-warning">{{ $mejor_marca }} km</h3>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mb-3">Mis Objetivos</h4>
    <div class="row mb-5">
        @forelse($lista_objetivos as $meta)
            <div class="col-md-6 mb-3">
                <div class="card stat-card p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">{{ $meta['titulo'] }}</span>
                        <span class="text-muted small">{{ $meta['actual'] }} / {{ $meta['meta'] }}</span>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-{{ $meta['color'] }} progress-bar-striped progress-bar-animated" 
                             style="width: {{ $meta['porcentaje'] }}%">
                            {{ round($meta['porcentaje']) }}%
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-light border">No tienes objetivos pendientes.</div></div>
        @endforelse
    </div>

    <div class="card stat-card p-4">
        <h5 class="fw-bold mb-3">Progreso de Calorías</h5>
        <canvas id="caloriesChart" height="100"></canvas>
    </div>
</div>
@endsection

@section('scripts_extra')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('caloriesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            // Blade inyecta el JSON directamente a JS
            labels: @json($labels),
            datasets: [{
                label: 'Kcal',
                data: @json($dataPoints),
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                fill: true,
                tension: 0.4
            }]
        }
    });
</script>
@endsection