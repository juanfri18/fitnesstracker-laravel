@extends('layouts.app')

@section('titulo', 'Estadísticas')

@section('css_extra')
<style>
    .stat-card { border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
</style>
@endsection

@section('contenido')
<div class="container py-5">
    <h2 class="fw-bold mb-4" style="color: var(--primary-color);">Tus Estadísticas</h2>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100">
                <small class="text-muted">Total Entrenamientos</small>
                <h3 class="fw-bold text-primary">{{ $totales['total_entrenos'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100">
                <small class="text-muted">Esta Semana</small>
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="fw-bold text-success mb-0">{{ $semana['sem_entrenos'] }} workouts</h3>
                    @if(isset($tendencia_porcentaje))
                        @if($tendencia_porcentaje > 0)
                            <span class="badge bg-success bg-opacity-25 text-success"><i class="fas fa-arrow-up me-1"></i>{{ $tendencia_porcentaje }}% vs ant</span>
                        @elseif($tendencia_porcentaje < 0)
                            <span class="badge bg-danger bg-opacity-25 text-danger"><i class="fas fa-arrow-down me-1"></i>{{ abs($tendencia_porcentaje) }}% vs ant</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-25 text-secondary"><i class="fas fa-minus me-1"></i>0% vs ant</span>
                        @endif
                    @endif
                </div>
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
                <small class="text-muted d-block">Carga Máxima</small>
                <h3 class="fw-bold text-warning">{{ $mejor_marca }} kg</h3>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mb-3">Mis Metas</h4>
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-fire-alt text-danger me-2"></i>Tiempo de Entrenamiento</h5>
                    <select id="periodoSelect" class="form-select form-select-sm" style="width: 150px;" onchange="updateChart()">
                        <option value="semana" selected>Últimos 7 días</option>
                        <option value="mes">Últimos 30 días</option>
                        <option value="anio">Último año</option>
                    </select>
                </div>
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
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
    let caloriesChartInst = null;
    
    function updateChart() {
        const periodo = document.getElementById('periodoSelect').value;
        const ctx = document.getElementById('caloriesChart').getContext('2d');
        
        fetch(`/api/metricas/dashboard?periodo=${periodo}`)
            .then(res => {
                if (!res.ok) throw new Error('Error en la respuesta del servidor');
                return res.json();
            })
            .then(data => {
                if(caloriesChartInst) caloriesChartInst.destroy();
                
                caloriesChartInst = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Minutos',
                            data: data.dataPoints,
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        plugins: { datalabels: { display: false } } // Desactivar en línea
                    }
                });
            })
            .catch(error => {
                console.error('Error cargando gráfico principal:', error);
                const canvas = document.getElementById('caloriesChart');
                if(canvas) {
                    canvas.outerHTML = '<div class="alert alert-warning text-center mt-4" id="caloriesChartError">No se pudieron cargar los datos de evolución.</div>';
                }
            });
    }

    // Inicializar al cargar
    updateChart();

    // Gráfico de Tipos (AJAX Asíncrono)
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    
    // Registrar el plugin para porcentajes en gráfica circular
    Chart.register(ChartDataLabels);

    fetch('/api/metricas/tipos')
        .then(res => {
            if (!res.ok) throw new Error('Error en la respuesta del servidor');
            return res.json();
        })
        .then(data => {
            const bgColors = data.labels.map(label => {
                const lp = label.toLowerCase();
                if (lp.includes('fuerza')) return '#dc3545'; // Rojo
                if (lp.includes('carrera') || lp.includes('correr') || lp.includes('running')) return '#198754'; // Verde
                if (lp.includes('caminata') || lp.includes('andar')) return '#0d6efd'; // Azul
                return '#6c757d'; // Default gris
            });

            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.dataPoints,
                        backgroundColor: bgColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { position: 'bottom' },
                        datalabels: {
                            color: '#ffffff',
                            font: { weight: 'bold', size: 14 },
                            formatter: (value, ctx) => {
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                                dataArr.forEach(data => { sum += Number(data); });
                                let percentage = (value * 100 / sum).toFixed(0) + "%";
                                return percentage;
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        })
        .catch(error => {
            console.error('Error cargando gráficas:', error);
            const container = document.getElementById('pieChart').parentNode;
            container.innerHTML = '<div class="alert alert-warning text-center mt-4">No se pudieron cargar los datos de la gráfica en este momento.</div>';
        });
</script>
@endsection