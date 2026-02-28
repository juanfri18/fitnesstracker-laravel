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
    <div class="row g-4 ">
        <div class="col-lg-4">
            <div class="card profile-card p-4 shadow-sm mb-4 ">
                <div class="text-center mb-3">
                    <div class="rounded-circle mx-auto mb-2" style="width: 80px; height: 80px; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        <i class="fas fa-user"></i>
                    </div>
                    <h4 class="fw-bold mb-0">¡Hola, Atleta!</h4>
                    <p class="text-muted small">"Preparando maratón"</p>
                </div>
                <div class="row g-2 mb-4">
                    <div class="col-6"><div class="stat-badge"><small class="d-block text-muted">Peso</small><span class="stat-value">78 kg</span></div></div>
                    <div class="col-6"><div class="stat-badge"><small class="d-block text-muted">Grasa</small><span class="stat-value">18%</span></div></div>
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
                <div class="alert alert-info text-center">
                    Todavía no has registrado ninguna actividad. ¡Empieza hoy!
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts_extra')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('miniChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['L','M','X','J','V','S','D'],
            datasets: [{ 
                data: [300, 450, 0, 550, 400, 700, 200], 
                borderColor: '#2A5199', tension: 0.4, fill: true, backgroundColor: 'rgba(42,81,153,0.1)' 
            }]
        },
        options: { plugins: { legend: {display: false} }, scales: { y: {display: false}, x: {grid: {display: false}} } }
    });
</script>
@endsection