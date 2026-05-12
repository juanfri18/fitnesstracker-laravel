@extends('layouts.app')

@section('titulo', 'Gestión de Objetivos')

@section('contenido')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--primary-color);">Mis Metas Personales</h2>
    </div>

    @if(session('msg'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('msg') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Formulario Nuevo Objetivo -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom-0 pb-0 pt-4">
                    <h5 class="fw-bold"><i class="fas fa-flag-checkered text-primary me-2"></i>Nueva Meta</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('objetivos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Apunto a conseguir...</label>
                            <select name="tipo_objetivo" id="tipoObjetivo" class="form-select" required onchange="actualizarFormulario()">
                                <option value="Volumen (kg levantados)">Volumen (kg levantados)</option>
                                <option value="Días Entrenados">Días Entrenados</option>
                                <option value="Peso Corporal">Meta de Peso Corporal (kg)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted" id="valorLabel">Valor (Número o cantidad)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-bullseye text-secondary"></i></span>
                                <input type="number" id="valorInput" step="5" min="1" name="valor_objetivo" class="form-control border-start-0" placeholder="Ej: 500" required>
                            </div>
                            <small class="text-muted mt-1 d-block" id="valorHint">Total de kg×series×reps dentro del rango de fechas.</small>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Fecha Límite</label>
                                <input type="date" name="fecha_limite" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius: 25px;">Crear Meta</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lista de Objetivos -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Tus metas activas y completadas</h5>
                    <div class="list-group">
                        @forelse($objetivos as $obj)
                            @php
                                $estado_real = $obj->estado_real;
                                $progreso = $obj->progreso ?? 0;
                                $meta = $obj->valor_objetivo;

                                if ($obj->tipo_objetivo === 'Peso Corporal') {
                                    if ($progreso > 0 && abs($progreso - $meta) <= 0.5) {
                                        $porcentaje = 100;
                                    } elseif ($progreso > 0 && $meta > 0) {
                                        $porcentaje = ($meta > $progreso)
                                            ? ($progreso / $meta) * 100
                                            : ($meta / $progreso) * 100;
                                    } else {
                                        $porcentaje = 0;
                                    }
                                } else {
                                    $porcentaje = ($meta > 0) ? ($progreso / $meta) * 100 : 0;
                                }
                                $porcentaje = min($porcentaje, 100);

                                $barColor = 'primary';
                                if ($estado_real === 'completado') $barColor = 'success';
                                elseif ($estado_real === 'caducado') $barColor = 'danger';
                            @endphp
                            <div class="list-group-item py-3 border-start-0 border-end-0 border-top-0 mb-3 {{ $estado_real !== 'en_progreso' ? 'bg-light' : '' }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">
                                            @if($estado_real === 'completado')
                                                <i class="fas fa-trophy text-warning me-1"></i>
                                            @elseif($estado_real === 'caducado')
                                                <i class="fas fa-clock text-danger me-1"></i>
                                            @else
                                                <i class="fas fa-crosshairs text-primary me-1"></i>
                                            @endif
                                            {{ ucfirst($obj->tipo_objetivo) }}
                                        </h6>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="badge {{ $estado_real === 'completado' ? 'bg-success' : ($estado_real === 'caducado' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                                {{ ucfirst(str_replace('_', ' ', $estado_real)) }}
                                            </span>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                @if($obj->fecha_inicio && $obj->fecha_limite)
                                                    {{ \Carbon\Carbon::parse($obj->fecha_inicio)->translatedFormat('d M Y') }} → {{ \Carbon\Carbon::parse($obj->fecha_limite)->translatedFormat('d M Y') }}
                                                @elseif($obj->fecha_limite)
                                                    Límite: {{ \Carbon\Carbon::parse($obj->fecha_limite)->translatedFormat('d M Y') }}
                                                @else
                                                    Sin fecha límite
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('objetivos.destroy', $obj->id) }}" method="POST" onsubmit="return confirm('¿Eliminar meta?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>

                                {{-- BARRA DE PROGRESO --}}
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="fw-bold text-muted">Progreso</small>
                                        <small class="text-muted">
                                            {{ round($progreso, 1) }} / {{ $meta }}
                                            @if(str_contains($obj->tipo_objetivo, 'kg') || str_contains($obj->tipo_objetivo, 'Volumen') || $obj->tipo_objetivo === 'Peso Corporal')
                                                kg
                                            @else
                                                días
                                            @endif
                                        </small>
                                    </div>
                                    <div class="progress" style="height: 22px; border-radius: 12px;">
                                        <div class="progress-bar bg-{{ $barColor }} progress-bar-striped {{ $estado_real === 'en_progreso' ? 'progress-bar-animated' : '' }}"
                                             role="progressbar"
                                             style="width: {{ $porcentaje }}%; min-width: {{ $porcentaje > 0 ? '2rem' : '0' }};"
                                             aria-valuenow="{{ $porcentaje }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                            {{ round($porcentaje) }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-award fs-1 text-muted mb-3 opacity-25"></i>
                                <h5 class="text-muted fw-bold">Aún no hay metas</h5>
                                <p class="text-muted small">Crea tu primer desafío para empezar a medir tu progreso.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts_extra')
<script>
    function actualizarFormulario() {
        const tipo = document.getElementById('tipoObjetivo').value;
        const input = document.getElementById('valorInput');
        const hint = document.getElementById('valorHint');
        const label = document.getElementById('valorLabel');

        if (tipo === 'Volumen (kg levantados)') {
            input.step = '5';
            input.min = '1';
            input.placeholder = 'Ej: 500';
            input.value = '';
            label.textContent = 'Volumen total (kg)';
            hint.textContent = 'Total de kg×series×reps dentro del rango de fechas.';
        } else if (tipo === 'Días Entrenados') {
            input.step = '1';
            input.min = '1';
            input.placeholder = 'Ej: 5';
            input.value = '';
            label.textContent = 'Número de días';
            hint.textContent = 'Días distintos con al menos 1 entreno registrado.';
        } else if (tipo === 'Peso Corporal') {
            input.step = '0.1';
            input.min = '1';
            input.placeholder = 'Ej: 60';
            input.value = '';
            label.textContent = 'Peso objetivo (kg)';
            hint.textContent = 'Se marca como completado al bajar o alcanzar este peso exacto.';
        }
    }

    // Inicializar al cargar la página
    document.addEventListener('DOMContentLoaded', actualizarFormulario);
</script>
@endsection
