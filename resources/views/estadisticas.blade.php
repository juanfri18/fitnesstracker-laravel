@extends('layouts.app')

@section('titulo', 'Estadísticas')

@section('css_extra')
<style>
    .stat-card { border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
</style>
@endsection

@section('contenido')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: var(--primary-color);">Tus Estadísticas</h2>
        <select class="form-select form-select-sm shadow-sm" style="width: 180px; border-radius: 20px; font-weight: bold; border-color: var(--primary-color); color: var(--primary-color);" onchange="window.location.href='/estadisticas?periodo=' + this.value">
            <option value="semana" {{ request('periodo', 'semana') == 'semana' ? 'selected' : '' }}>🗓️ Esta Semana</option>
            <option value="mes" {{ request('periodo') == 'mes' ? 'selected' : '' }}>📅 Este Mes</option>
            <option value="anio" {{ request('periodo') == 'anio' ? 'selected' : '' }}>📆 Este Año</option>
        </select>
    </div>

    {{-- 4 TARJETAS DE RESUMEN --}}
    <div class="row g-4 mb-3">
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100">
                <small class="text-muted">Total Entrenamientos</small>
                <h3 class="fw-bold text-primary">{{ $totales['total_entrenos'] }}</h3>
                <small class="text-muted" style="font-size: 0.7rem;">Desde que creaste tu cuenta</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100">
                <small class="text-muted">Este Periodo</small>
                <h3 class="fw-bold text-success mb-0">{{ $semana['sem_entrenos'] }} entrenos</h3>
                <small class="text-muted" style="font-size: 0.7rem;">
                    @if($periodo_actual === 'semana') En la semana actual
                    @elseif($periodo_actual === 'mes') En el mes actual
                    @else En el año actual
                    @endif
                </small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100">
                <small class="text-muted">Tiempo Total</small>
                <h3 class="fw-bold text-info">{{ floor($totales['total_min']/60) }}h {{ $totales['total_min']%60 }}m</h3>
                <small class="text-muted" style="font-size: 0.7rem;">Minutos acumulados globales</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100">
                <small class="text-muted d-block">Carga Máxima</small>
                <h3 class="fw-bold text-warning">{{ $mejor_marca }} kg</h3>
                <small class="text-muted" style="font-size: 0.7rem;">Tu récord personal de peso</small>
            </div>
        </div>
    </div>

    {{-- BLOQUE COMPARATIVA (sacado fuera de las tarjetas) --}}
    <div class="card stat-card p-3 mb-4" style="border-left: 4px solid {{ $tendencia_porcentaje > 0 ? '#198754' : ($tendencia_porcentaje < 0 ? '#dc3545' : '#6c757d') }};">
        <div class="d-flex align-items-center">
            @if($tendencia_porcentaje > 0)
                <div class="me-3 p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(25, 135, 84, 0.1);">
                    <i class="fas fa-arrow-up text-success fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-success">+{{ $tendencia_porcentaje }}% respecto a {{ $periodo_label }}</h6>
                    <small class="text-muted">Has hecho <strong>{{ $semana['sem_entrenos'] - $periodo_anterior_entrenos }} entreno(s) más</strong> que {{ $periodo_label }}. ¡Sigue así!</small>
                </div>
            @elseif($tendencia_porcentaje < 0)
                <div class="me-3 p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(220, 53, 69, 0.1);">
                    <i class="fas fa-arrow-down text-danger fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-danger">{{ $tendencia_porcentaje }}% respecto a {{ $periodo_label }}</h6>
                    <small class="text-muted">Has hecho <strong>{{ $periodo_anterior_entrenos - $semana['sem_entrenos'] }} entreno(s) menos</strong> que {{ $periodo_label }}. ¡Puedes recuperar el ritmo!</small>
                </div>
            @else
                <div class="me-3 p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(108, 117, 125, 0.1);">
                    <i class="fas fa-equals text-secondary fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-secondary">Sin cambios respecto a {{ $periodo_label }}</h6>
                    <small class="text-muted">
                        @if($semana['sem_entrenos'] == 0 && $periodo_anterior_entrenos == 0)
                            No tienes entrenamientos registrados en ninguno de los dos periodos.
                        @else
                            Has entrenado lo mismo que {{ $periodo_label }} ({{ $semana['sem_entrenos'] }} entrenos).
                        @endif
                    </small>
                </div>
            @endif
        </div>
    </div>

    {{-- METAS --}}
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

    {{-- GRÁFICAS --}}
    <div class="row g-4">
        <!-- Gráfico Principal (Lineal) -->
        <div class="col-lg-8">
            <div class="card stat-card p-4 h-100">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1"><i class="fas fa-chart-line text-danger me-2"></i>Evolución de Minutos Entrenados</h5>
                    <small class="text-muted">
                        @if($periodo_actual === 'semana')
                            Minutos de entrenamiento por día en los <strong>últimos 7 días</strong> (hasta hoy).
                        @elseif($periodo_actual === 'mes')
                            Minutos de entrenamiento <strong>cada día del mes actual</strong>.
                        @else
                            Minutos de entrenamiento acumulados <strong>cada mes del año {{ now()->year }}</strong>.
                        @endif
                    </small>
                </div>
                <canvas id="caloriesChart"></canvas>
            </div>
        </div>

        <!-- Gráfico Secundario (Doughnut) -->
        <div class="col-lg-4">
            <div class="card stat-card p-4 h-100">
                <h5 class="fw-bold mb-1"><i class="fas fa-chart-pie text-primary me-2"></i>Distribución de Entrenos</h5>
                <small class="text-muted d-block mb-3">Porcentaje de tus entrenamientos por tipo (Fuerza, Carrera, Caminata) a lo largo de toda tu cuenta.</small>
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
        const periodo = "{{ request('periodo', 'semana') }}";
        const ctx = document.getElementById('caloriesChart').getContext('2d');
        
        fetch(`/api/metricas/dashboard?periodo=${periodo}`)
            .then(res => {
                if (!res.ok) throw new Error('Error en la respuesta del servidor');
                return res.json();
            })
            .then(data => {
                if(caloriesChartInst) caloriesChartInst.destroy();
                
                // Puntos más pequeños cuantos más datos hay (ej: 31 días del mes)
                const numPuntos = data.labels.length;
                const pRadius = numPuntos > 15 ? 2 : 4;
                const pHoverRadius = numPuntos > 15 ? 4 : 6;
                const borderW = numPuntos > 15 ? 1.5 : 2;

                caloriesChartInst = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: periodo === 'anio' ? 'Minutos por mes' : 'Minutos por día',
                            data: data.dataPoints,
                            borderColor: '#dc3545',
                            borderWidth: borderW,
                            backgroundColor: 'rgba(220, 53, 69, 0.08)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: pRadius,
                            pointBackgroundColor: '#dc3545',
                            pointHoverRadius: pHoverRadius
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2.2,
                        plugins: { 
                            datalabels: { display: false },
                            legend: { display: true, position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        return ctx.parsed.y + ' minutos';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    maxRotation: numPuntos > 15 ? 45 : 0,
                                    autoSkip: numPuntos > 15,
                                    maxTicksLimit: 15,
                                    font: { size: numPuntos > 15 ? 10 : 12 }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: { display: true, text: 'Minutos', font: { weight: 'bold' } }
                            }
                        }
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