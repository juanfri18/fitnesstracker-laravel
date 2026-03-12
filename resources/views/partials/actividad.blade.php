<div class="card post-card shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <div class="text-white me-3 p-2 rounded" style="background-color: var(--primary-color);">
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
    </div>
</div>
