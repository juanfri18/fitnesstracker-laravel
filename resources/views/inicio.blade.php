@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('css_extra')
<style>
    .profile-card, .post-card { border: none; border-radius: 15px; background: white; }
    .stat-badge { background: #eef2f7; border-radius: 10px; padding: 10px; text-align: center; }
    .stat-value { color: var(--primary-color); font-weight: 700; }
    .section-title { font-weight: 700; border-left: 5px solid var(--primary-color); padding-left: 15px; }
    .btn-add { background-color: var(--primary-color); color: white; border-radius: 25px; font-weight: 600; padding: 10px 20px; text-decoration: none; display: inline-block;}
    .btn-add:hover { background-color: #1e3c72; color: white; }
</style>
@endsection

@section('contenido')
<div class="container-fluid px-4 mt-4">
    <div class="row g-4 align-items-start">
        <div class="col-lg-4 sticky-lg-top" style="top: 80px; padding-top: 80px;">
            @php
                $perfil = Auth::user()->perfil;
                $metrica = Auth::user()->metricaActual;
                $peso = $metrica->peso ?? null;
                $altura = $perfil->altura ?? null;
                $edad = $perfil->edad ?? null;
                $genero = $perfil->genero ?? null;
                $grasa = null;
                if ($peso && $altura && $edad && $genero) {
                    $altura_metros = $altura / 100;
                    $imc = $peso / ($altura_metros * $altura_metros);
                    $factor_genero = ($genero === 'Hombre') ? 1 : 0;
                    $grasa = (1.20 * $imc) + (0.23 * $edad) - (10.8 * $factor_genero) - 5.4;
                    $grasa = max(1, min(60, round($grasa, 1)));
                }
            @endphp
            <div class="card profile-card p-4 shadow-sm mb-4">
                <div class="text-center mb-3">
                    <div class="rounded-circle mx-auto mb-2 overflow-hidden" style="width: 80px; height: 80px; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        @if($perfil && $perfil->foto)
                            <img src="{{ asset('storage/' . $perfil->foto) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                    <h4 class="fw-bold mb-0">¡Hola, {{ Auth::user()->nombre }}!</h4>
                    <p class="text-muted small">{{ ($perfil && $perfil->biografia) ? '"'.$perfil->biografia.'"' : '' }}</p>
                </div>
                <div class="row g-2 mb-4">
                    <div class="col-6"><div class="stat-badge"><small class="d-block text-muted">Peso</small><span class="stat-value">{{ $peso ?? '-- '}} kg</span></div></div>
                    <div class="col-6"><div class="stat-badge"><small class="d-block text-muted">Grasa</small><span class="stat-value">{{ $grasa ?? '-- '}} %</span></div></div>
                </div>
                <h6 class="fw-bold small text-muted">Progreso Semanal</h6>
                <canvas id="miniChart" height="150"></canvas>
                <div class="d-grid mt-4">
                    <a href="/registro" class="btn btn-add text-center"><i class="fas fa-plus me-2"></i>Nueva Actividad</a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <h4 class="section-title">Actividad Reciente</h4>

            @forelse ($actividades as $actividad)
                @include('partials.actividad', ['actividad' => $actividad])
            @empty
                {{-- #9: Onboarding para usuario nuevo --}}
                <div class="card shadow-sm border-0 p-5 text-center" style="border-radius: 15px;">
                    <div class="mb-4">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: rgba(42, 81, 153, 0.1);">
                            <i class="fas fa-rocket text-primary fs-1"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold">¡Bienvenido a SinergyFit!</h4>
                    <p class="text-muted mb-4">Aún no tienes entrenamientos registrados.<br>Empieza hoy y desbloquea tu primer logro 🏆</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="/registro" class="btn btn-primary rounded-pill fw-bold px-4"><i class="fas fa-plus me-2"></i>Registrar primer entreno</a>
                        <a href="/perfil" class="btn btn-outline-secondary rounded-pill fw-bold px-4"><i class="fas fa-user me-2"></i>Completar mi perfil</a>
                    </div>
                </div>
            @endforelse

            @if(count($actividades) >= 5)
                <div class="d-flex justify-content-center mt-3 mb-4 w-100">
                    <a href="/historial" class="btn btn-primary shadow-sm" style="border-radius: 30px; padding: 12px 35px; font-weight: bold; font-size: 1.05rem;">
                        Ver todos los entrenamientos <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- #26: Modal de celebración de logros --}}
@if(session('logros_nuevos'))
<div class="modal fade" id="logroModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-body p-5" style="background: linear-gradient(135deg, #2A5199 0%, #1e3c72 100%); color: white;">
                <div class="mb-3">
                    <i class="fas fa-trophy fa-4x" style="color: #ffc107; filter: drop-shadow(0 4px 8px rgba(255, 193, 7, 0.5));"></i>
                </div>
                <h3 class="fw-bold mb-2">🎉 ¡Logro Desbloqueado!</h3>
                @foreach(session('logros_nuevos') as $nombre_logro)
                    <h4 class="fw-bold mb-1" style="color: #ffc107;">{{ $nombre_logro }}</h4>
                @endforeach
                <p class="mt-3 mb-0 small opacity-75">¡Sigue así, campeón! Revisa tu vitrina de logros en tu perfil.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center py-3">
                <button type="button" class="btn btn-primary rounded-pill fw-bold px-5" data-bs-dismiss="modal">
                    <i class="fas fa-fist-raised me-2"></i>¡Vamos!
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts_extra')
<script src="/vendor/chartjs/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('miniChart').getContext('2d');
    
    // Fetch data asynchronously (AJAX - SPA approach)
    fetch('/api/metricas/dashboard')
        .then(response => response.json())
        .then(data => {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{ 
                        data: data.dataPoints, 
                        borderColor: '#2A5199', tension: 0.4, fill: true, backgroundColor: 'rgba(42,81,153,0.1)' 
                    }]
                },
                options: { plugins: { legend: {display: false} }, scales: { y: {display: false}, x: {grid: {display: false}} } }
            });
        })
        .catch(error => {
            console.error("Error loading AJAX chart data:", error);
            const canvas = document.getElementById('miniChart');
            if(canvas) {
                canvas.outerHTML = '<div class="alert alert-warning text-center small mt-2"><i class="fas fa-exclamation-triangle me-1"></i>No se pudo cargar el gráfico.</div>';
            }
        });

    // #26: Confetti celebration
    @if(session('logros_nuevos'))
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('logroModal'));
            modal.show();
            // Fire confetti
            confetti({ particleCount: 150, spread: 80, origin: { y: 0.6 } });
            setTimeout(() => confetti({ particleCount: 100, spread: 100, origin: { y: 0.5 } }), 500);
            setTimeout(() => confetti({ particleCount: 80, spread: 120, origin: { y: 0.7 } }), 1000);
        });
    @endif
</script>
@endsection