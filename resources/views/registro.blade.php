@extends('layouts.app')

@section('titulo', 'Registrar Actividad')

@section('css_extra')
<style>
    .card-custom { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .form-section { display: none; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px; }
    .btn-save { background-color: var(--primary-color); color: white; font-weight: bold; padding: 12px; border-radius: 25px; border: none; width: 100%; transition: 0.3s; }
    .btn-save:hover { background-color: #1e3c72; }
</style>
@endsection

@section('contenido')
<div class="container-fluid px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom p-4 p-md-5 bg-white">
                <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-color);">Registrar Actividad</h3>
                
                <form id="fitnessForm" action="/entrenamientos" method="POST">
                    @csrf 
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Fecha</label>
                            <input type="date" class="form-control" id="date" name="fecha" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Duración (min)</label>
                            <input type="number" class="form-control" id="time" name="tiempo" placeholder="Minutos" required oninput="pace()">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Módulo / Categoría</label>
                            <select class="form-select border-primary fw-bold" id="mainCat" name="modulo" required onchange="toggleModule()">
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="fuerza">Entrenamiento de Fuerza</option>
                                <option value="carrera">Carrera</option>
                                <option value="caminata">Caminata</option>
                            </select>
                        </div>
                    </div>

                    <div id="sec-fuerza" class="form-section animate__animated animate__fadeIn">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-primary mb-0"><i class="fas fa-dumbbell me-2"></i>Detalle Musculación</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="addExerciseRow()"><i class="fas fa-plus"></i> Añadir Ejercicio</button>
                        </div>
                        
                        <div id="exercises-container">
                            <!-- Fila de ejercicio base -->
                            <div class="exercise-row p-3 mb-3 bg-light rounded border position-relative">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small fw-bold">Grupo Muscular</label>
                                        <select class="form-select group-select" name="grupo_muscular[]" onchange="loadEx(this)">
                                            <option value="">Seleccione...</option>
                                            <option value="pecho">Pecho</option>
                                            <option value="espalda">Espalda</option>
                                            <option value="pierna">Pierna</option>
                                            <option value="hombro">Hombro</option>
                                            <option value="brazo">Brazos (Bíceps/Tríceps)</option>
                                            <option value="core">Core / Abdominales</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-bold">Ejercicio</label>
                                        <select class="form-select ex-select" name="ejercicio[]">
                                            <option>Seleccione grupo primero...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-4"><label class="small fw-bold">Nº Series</label><input type="number" name="series[]" class="form-control" placeholder="Ej: 4"></div>
                                    <div class="col-4"><label class="small fw-bold">Repeticiones</label><input type="number" name="reps[]" class="form-control" placeholder="Ej: 12"></div>
                                    <div class="col-4"><label class="small fw-bold">Carga (Kg)</label><input type="number" name="carga[]" step="0.5" class="form-control" placeholder="Ej: 60"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="sec-cardio" class="form-section animate__animated animate__fadeIn">
                        <h5 id="cardioTitle" class="mb-3"><i class="fas fa-running me-2"></i>Detalle Cardio</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6"><label class="small fw-bold">Distancia (km)</label><input type="number" step="0.01" class="form-control" id="dist" name="distancia" oninput="pace()"></div>
                            <div class="col-md-6"><label class="small fw-bold">Altitud (m)</label><input type="number" class="form-control" name="altitud" placeholder="Desnivel"></div>
                        </div>
                        
                        <div class="alert alert-light border text-center fw-bold mb-3">
                            Ritmo Medio: <span id="paceRes" class="text-primary">--:--</span> min/km
                        </div>
                    </div>

                    <div id="sec-common" class="form-section">
                        <label class="form-label fw-bold mb-3">Sensación Percibida (Cansancio 1-10)</label>
                        <input type="range" class="form-range" min="1" max="10" id="feel" name="sensacion" value="5" oninput="document.getElementById('feelVal').innerText = this.value">
                        <div class="d-flex justify-content-between text-muted small px-1">
                            <span>Muy Fresco</span>
                            <span id="feelVal" class="badge bg-primary fs-6">5</span>
                            <span>Agotado</span>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-save shadow">Guardar Actividad</button>
                            <a href="/" class="btn btn-link text-muted text-center text-decoration-none">Cancelar y Volver</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts_extra')
<script>
    const exercises = { 
        pecho: ['Press Banca', 'Aperturas', 'Flexiones', 'Press Inclinado', 'Cruce de Poleas'], 
        espalda: ['Dominadas', 'Remo con Barra', 'Jalón al Pecho', 'Remo en Polea Baja', 'Peso Muerto'],
        pierna: ['Sentadillas', 'Prensa', 'Zancadas', 'Curl Femoral', 'Extensión de Cuádriceps', 'Gemelos'],
        hombro: ['Press Militar', 'Elevaciones Laterales', 'Pájaros', 'Elevaciones Frontales'],
        brazo: ['Curl de Bíceps', 'Curl Martillo', 'Press Francés', 'Extensión de Tríceps Polea', 'Fondos'],
        core: ['Plancha', 'Crunch', 'Elevación de Piernas', 'Rueda Abdominal']
    };

    function toggleModule() {
        const val = document.getElementById('mainCat').value;
        document.getElementById('sec-fuerza').style.display = val === 'fuerza' ? 'block' : 'none';
        document.getElementById('sec-cardio').style.display = (val === 'carrera' || val === 'caminata') ? 'block' : 'none';
        document.getElementById('sec-common').style.display = 'block';

        if(val === 'carrera' || val === 'caminata'){
            const title = document.getElementById('cardioTitle');
            if(val === 'carrera') { title.innerHTML = '<i class="fas fa-running me-2"></i>Módulo Running'; title.className = "mb-3 text-danger"; }
            else { title.innerHTML = '<i class="fas fa-walking me-2"></i>Módulo Caminata'; title.className = "mb-3 text-success"; }
        }
    }

    // Actualizado para funcionar con múltiples filas
    function loadEx(selectElement) {
        const g = selectElement.value;
        // Buscar el select de ejercicios que esté en el mismo "exercise-row" que este desplegable
        const l = selectElement.closest('.exercise-row').querySelector('.ex-select');
        
        l.innerHTML = '<option value="">Seleccione...</option>'; 
        if(exercises[g]) {
            exercises[g].forEach(e => l.innerHTML += `<option value="${e}">${e}</option>`);
        }
    }

    function addExerciseRow() {
        const container = document.getElementById('exercises-container');
        // Clonamos el primer ejercicio (que siempre será la plantilla Base)
        const firstRow = container.querySelector('.exercise-row');
        const newRow = firstRow.cloneNode(true);
        
        // Limpiamos los valores clonados
        newRow.querySelectorAll('input, select').forEach(input => {
            if(input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
        });

        // Limpiamos la lista de ejercicios del clon
        const exSelect = newRow.querySelector('.ex-select');
        exSelect.innerHTML = '<option>Seleccione grupo primero...</option>';

        // Añadimos botón de eliminar en la parte superior derecha si no existe
        if(!newRow.querySelector('.btn-remove-row')) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn-close btn-remove-row position-absolute top-0 end-0 m-2';
            btn.onclick = function() { removeExerciseRow(this); };
            newRow.appendChild(btn);
        }

        // Añadimos la nueva fila al contenedor añadiendo una pequeña animación
        newRow.classList.add('animate__animated', 'animate__fadeInDown');
        container.appendChild(newRow);
    }

    function removeExerciseRow(btn) {
        const container = document.getElementById('exercises-container');
        // Aseguramos que siempre quede al menos una fila
        if(container.querySelectorAll('.exercise-row').length > 1) {
            btn.closest('.exercise-row').remove();
        } else {
            alert('Debes incluir al menos un ejercicio de fuerza.');
        }
    }

    function pace() {
        const d = parseFloat(document.getElementById('dist').value);
        const t = parseFloat(document.getElementById('time').value);
        if(d && t) { 
            const p = t/d; 
            document.getElementById('paceRes').innerText = `${Math.floor(p)}:${Math.round((p%1)*60).toString().padStart(2,'0')}`; 
        }
    }

    document.getElementById('date').valueAsDate = new Date();
</script>
@endsection