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
                    <small class="text-muted">{{ date('d/m/Y', strtotime($actividad['fecha'])) }}</small>
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

        <div class="row bg-light rounded p-3 mx-1 mb-3">
            <div class="col-4 text-center border-end">
                <small class="d-block text-muted">Duración</small>
                <span class="fw-bold">{{ $actividad['duracion_minutos'] }} min</span>
            </div>
            <div class="col-8 text-center text-muted small px-3">
                <i class="fas fa-info-circle me-1"></i>
                <span class="fst-italic">{{ $actividad['notas'] ?: 'Sin detalles adicionales' }}</span>
            </div>
        </div>

        @if(!empty($actividad['detalles']))
            <div class="mx-1 mb-2">
                <h6 class="small fw-bold text-secondary mb-2"><i class="fas fa-list-ul me-2"></i>Ejercicios Realizados</h6>
                <ul class="list-group list-group-flush small" style="border-radius: 10px; overflow: hidden; border: 1px solid #eee;">
                    @foreach($actividad['detalles'] as $detalle)
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light border-bottom border-white">
                            <span>
                                <span class="fw-bold text-dark">{{ $detalle['ejercicio']['nombre'] ?? 'Ejercicio' }}</span>
                                <span class="text-muted ms-1">({{ $detalle['grupo_muscular'] }})</span>
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
