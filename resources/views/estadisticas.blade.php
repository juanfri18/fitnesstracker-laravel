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
                        <span class="text-muted small">{{ round($meta['actual'], 1) }} / {{ $meta['meta'] }}</span>
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

    <div class="row g-4">
        <!-- Gráfico Principal (Lineal) -->
        <div class="col-lg-8">
            <div class="card stat-card p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="fas fa-fire-alt text-danger me-2"></i>Progreso de Calorías</h5>
                <canvas id="caloriesChart" height="100"></canvas>
            </div>
        </div>

        <!-- Gráfico Secundario (Doughnut) -->
        <div class="col-lg-4">
            <div class="card stat-card p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="fas fa-chart-pie text-primary me-2"></i>Distribución de Entrenos</h5>
                <div style="position: relative; height:250px; width:100%">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts_extra')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Calorías (PHP Inicializado)
    const ctx = document.getElementById('caloriesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
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

    // Gráfico de Tipos (AJAX Asíncrono)
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    fetch('/api/metricas/tipos')
        .then(res => res.json())
        .then(data => {
            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.dataPoints,
                        backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '70%'
                }
            });
        });
</script>
@endsection