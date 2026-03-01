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
                    <h5 class="fw-bold"><i class="fas fa-flag-checkered text-primary me-2"></i>Nuevo Objetivo</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('objetivos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Apunto a conseguir...</label>
                            <select name="tipo" class="form-select" required>
                                <option value="Distancia Mensual">Recorrer Distancia Mensual (km)</option>
                                <option value="Frecuencia Semanal">Frecuencia Semanal (Días de entreno)</option>
                                <option value="Peso Corporal">Objetivo de Peso Corporal (kg)</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Meta (Número o cantidad)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-bullseye text-secondary"></i></span>
                                <input type="number" step="0.1" min="1" name="valor_objetivo" class="form-control border-start-0" placeholder="Ej: 50" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius: 25px;">Crear Objetivo</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lista de Objetivos -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Tus objetivos activos y completados</h5>
                    <div class="list-group">
                        @forelse($objetivos as $obj)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-start-0 border-end-0 border-top-0 mb-2 {{ $obj->estado === 'Completado' ? 'bg-light' : '' }}">
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        {{ ucfirst($obj->tipo) }}: {{ $obj->valor_objetivo }}
                                    </h6>
                                    <span class="badge {{ $obj->estado === 'Completado' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $obj->estado }}
                                    </span>
                                    <small class="text-muted ms-2"><i class="far fa-calendar-alt me-1"></i>{{ $obj->fecha_limite ? 'Límite: ' . \Carbon\Carbon::parse($obj->fecha_limite)->format('d/m/Y') : 'Sin fecha límite' }}</small>
                                </div>
                                <div class="d-flex gap-2">
                                    @if($obj->estado !== 'Completado')
                                        <form action="{{ route('objetivos.update', $obj->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="estado" value="Completado">
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Marcar completado"><i class="fas fa-check"></i></button>
                                        </form>
                                    @endif
                                    <form action="{{ route('objetivos.destroy', $obj->id) }}" method="POST" onsubmit="return confirm('¿Eliminar objetivo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-award fs-1 text-muted mb-3 opacity-25"></i>
                                <h5 class="text-muted fw-bold">Aún no hay objetivos</h5>
                                <p class="text-muted small">Crea tu primer desafío para empezar a medir tu progreso semanal.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
