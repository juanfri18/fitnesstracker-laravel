<div class="card post-card shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                @php
                    $bgClass = 'bg-primary';
                    if($actividad['tipo'] == 'Fuerza') $bgClass = 'bg-danger';
                    elseif($actividad['tipo'] == 'Carrera') $bgClass = 'bg-success';
                @endphp
                <div class="text-white me-3 p-2 rounded {{ $bgClass }}">
                    @if($actividad['tipo'] == 'Fuerza')
                        <i class="fas fa-dumbbell fs-4"></i>
                    @elseif($actividad['tipo'] == 'Carrera')
                        <i class="fas fa-running fs-4"></i>
                    @else
                        <i class="fas fa-walking fs-4"></i>
                    @endif
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">{{ $actividad['tipo'] }}</h6>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($actividad['fecha'])->translatedFormat('d M Y') }}</small>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <a href="/entrenamientos/{{ $actividad['id'] }}/edit" class="btn btn-outline-primary btn-sm rounded-circle" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </a>
                <form action="/entrenamientos/{{ $actividad['id'] }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que quieres borrar este entreno?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" title="Eliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>

        @php
            // Extraer calorías de las notas para mostrarlas visualmente
            $kcal = null;
            if(preg_match('/Aprox:\s*(\d+)\s*kcal/', $actividad['notas'] ?? '', $m)) {
                $kcal = $m[1];
            }
            $notas_limpio = preg_replace('/\s*\|\s*Aprox:\s*\d+\s*kcal/', '', $actividad['notas'] ?? '');
        @endphp

        <div class="row bg-light rounded p-3 mx-1 mb-3">
            <div class="col-4 text-center border-end">
                <small class="d-block text-muted">Duración</small>
                <span class="fw-bold">{{ $actividad['duracion_minutos'] }} min</span>
            </div>
            <div class="col-4 text-center {{ $kcal ? 'border-end' : '' }}">
                @if($kcal)
                    <small class="d-block text-muted"><i class="fas fa-fire text-danger"></i> Calorías</small>
                    <span class="fw-bold text-danger">{{ $kcal }} kcal</span>
                @else
                    <small class="d-block text-muted">Calorías</small>
                    <span class="fw-bold text-muted">--</span>
                @endif
            </div>
            <div class="col-4 text-center text-muted small px-2">
                <small class="d-block text-muted">Detalle</small>
                <span class="fst-italic small">{{ $notas_limpio ?: 'Sin notas' }}</span>
            </div>
        </div>

        @if(!empty($actividad['detalles']))
            <div class="mx-1 mb-2">
                <h6 class="small fw-bold text-secondary mb-2"><i class="fas fa-list-ul me-2"></i>Ejercicios Realizados</h6>
                <ul class="list-group list-group-flush small" style="border-radius: 10px; overflow: hidden; border: 1px solid var(--border-color);">
                    @foreach($actividad['detalles'] as $detalle)
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light border-bottom">
                            <span>
                                <span class="fw-bold text-dark">{{ $detalle['ejercicio']['nombre'] ?? 'Ejercicio' }}</span>
                                <span class="text-muted ms-1">({{ $detalle['ejercicio']['grupo_muscular'] ?? '' }})</span>
                            </span>
                            <span class="badge bg-secondary rounded-pill">
                                {{ $detalle['series'] }}x{{ $detalle['repeticiones'] }} @if($detalle['carga_kg']) | {{ $detalle['carga_kg'] }}kg @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
