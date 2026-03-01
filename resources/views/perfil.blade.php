@extends('layouts.app')

@section('titulo', 'Mi Perfil')

@section('css_extra')
<style>
    .card-profile { border: none; border-radius: 15px; overflow: hidden; }
    .profile-header { height: 100px; background: linear-gradient(135deg, var(--primary-color), #1e3c72); }
    .avatar-container { margin-top: -50px; text-align: center; position: relative; }
    .profile-img { width: 120px; height: 120px; border-radius: 50%; border: 5px solid white; object-fit: cover; background: #ddd; }
    .btn-camera { position: absolute; bottom: 0; right: 50%; margin-right: -60px; background: white; border-radius: 50%; padding: 8px; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
    .section-title { color: var(--primary-color); font-weight: 700; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; margin-top: 20px; }
    .btn-save { background-color: var(--primary-color); color: white; border-radius: 25px; padding: 10px 30px; font-weight: 600; border:none; }
</style>
@endsection

@section('contenido')
<div class="container-fluid px-4 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-profile shadow-sm bg-white position-relative">
                <div class="profile-header"></div>
                <button type="button" id="btnEdit" class="btn btn-light position-absolute top-0 end-0 m-3 shadow-sm" style="border-radius: 20px; font-weight: bold;" onclick="toggleEditMode()">
                    <i class="fas fa-pencil-alt text-primary me-2"></i>Editar Perfil
                </button>
                <div class="card-body p-4">
                    <form action="/perfil" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="avatar-container mb-4">
                            <img src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://via.placeholder.com/120?text=Usuario' }}" id="preview" class="profile-img">
                            <input type="file" name="foto" id="fileUp" hidden onchange="loadImg(event)" accept="image/*" disabled>
                            <label for="fileUp" id="btnCamera" class="btn-camera d-none" title="Cambiar foto"><i class="fas fa-camera text-primary"></i></label>
                            <h3 class="mt-2 fw-bold mb-0">{{ $user->name ?? '' }}</h3>
                            <p class="text-muted fst-italic" id="bioDisplay">{{ $user->biografia ? '"'.$user->biografia.'"' : '' }}</p>
                        </div>

                        <h5 class="section-title"><i class="fas fa-user me-2"></i>Datos Personales</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="small fw-bold">Nombre</label>
                                <input type="text" name="nombre" class="form-control editable-field" value="{{ $user->name ?? '' }}" required readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control editable-field" value="{{ $user->apellidos ?? '' }}" placeholder="Opcional" readonly>
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold">Biografía / Estado</label>
                                <input type="text" name="biografia" class="form-control editable-field" id="bioInput" value="{{ $user->biografia ?? '' }}" oninput="updateBio()" readonly>
                            </div>
                        </div>

                        <h5 class="section-title mt-4"><i class="fas fa-weight me-2"></i>Datos Físicos y Deportivos</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="small fw-bold">Edad</label>
                                <input type="number" name="edad" class="form-control editable-field" value="{{ $user->edad ?? '' }}" placeholder="Ej: 28" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold">Altura (cm)</label>
                                <input type="number" name="altura" class="form-control editable-field" value="{{ $user->altura ?? '' }}" placeholder="Ej: 175" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold">Género</label>
                                <select name="genero" class="form-select editable-field" disabled style="background-color: #e9ecef;">
                                    <option value="" disabled {{ empty($user->genero) ? 'selected' : '' }}>Selecciona...</option>
                                    <option value="Hombre" {{ $user->genero === 'Hombre' ? 'selected' : '' }}>Hombre</option>
                                    <option value="Mujer" {{ $user->genero === 'Mujer' ? 'selected' : '' }}>Mujer</option>
                                </select>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="small fw-bold">Peso (kg)</label>
                                <input type="number" step="0.1" name="peso" class="form-control editable-field" value="{{ $user->peso ?? '' }}" placeholder="Ej: 75.5" readonly>
                            </div>
                            <div class="col-md-8 mt-3">
                                <label class="small fw-bold">Nivel de Actividad</label>
                                <select name="nivel_actividad" class="form-select editable-field" disabled style="background-color: #e9ecef;">
                                    <option value="" disabled {{ empty($user->nivel_actividad) ? 'selected' : '' }}>Selecciona tu nivel diario...</option>
                                    <option value="Sedentario" {{ $user->nivel_actividad === 'Sedentario' ? 'selected' : '' }}>Sedentario (Poco o nada de ejercicio)</option>
                                    <option value="Ligero" {{ $user->nivel_actividad === 'Ligero' ? 'selected' : '' }}>Ligero (Ejercicio 1-3 días extra a la semana)</option>
                                    <option value="Moderado" {{ $user->nivel_actividad === 'Moderado' ? 'selected' : '' }}>Moderado (Ejercicio 3-5 días extra a la semana)</option>
                                    <option value="Intenso" {{ $user->nivel_actividad === 'Intenso' ? 'selected' : '' }}>Intenso (Ejercicio 6-7 días a la semana)</option>
                                    <option value="Muy Intenso" {{ $user->nivel_actividad === 'Muy Intenso' ? 'selected' : '' }}>Muy Intenso (Doble turno o trabajo físico fuerte)</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="small fw-bold text-primary">Grasa Corporal (%) <span class="badge bg-primary ms-1" style="font-size: 0.6rem;">Auto-calculado</span></label>
                                <input type="text" name="grasa" class="form-control" value="{{ $user->grasa ?? 'Pendiente de datos' }}" readonly style="background-color: #f8f9fa; font-weight: bold; color: var(--primary-color);">
                                <small class="text-muted" style="font-size: 0.7rem;">Rellena peso, altura, edad y género para calcularlo.</small>
                            </div>
                        </div>

                        <div class="text-center mt-5 d-none" id="saveContainer">
                            <button type="submit" class="btn btn-save shadow-sm"><i class="fas fa-save me-2"></i>Guardar Cambios</button>
                            <button type="button" class="btn btn-light ms-2 shadow-sm" style="border-radius: 25px; padding: 10px 30px; font-weight: 600;" onclick="window.location.reload()">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts_extra')
<script>
    function toggleEditMode() {
        const fields = document.querySelectorAll('.editable-field');
        const fileUp = document.getElementById('fileUp');
        const btnCamera = document.getElementById('btnCamera');
        const saveContainer = document.getElementById('saveContainer');
        const btnEdit = document.getElementById('btnEdit');

        // Toggle readonly on text/number inputs
        fields.forEach(field => {
            if (field.tagName === 'SELECT') {
                field.toggleAttribute('disabled');
                if(!field.hasAttribute('disabled')) {
                    field.style.backgroundColor = '#fff';
                    field.style.border = '1px solid var(--primary-color)';
                } else {
                    field.style.backgroundColor = '#e9ecef';
                    field.style.border = '1px solid #dee2e6';
                }
            } else {
                field.toggleAttribute('readonly');
                // Add a slight visual cue when editable
                if(!field.hasAttribute('readonly')) {
                    field.style.backgroundColor = '#fff';
                    field.style.border = '1px solid var(--primary-color)';
                } else {
                    field.style.backgroundColor = '#e9ecef';
                    field.style.border = '1px solid #dee2e6';
                }
            }
        });

        // Toggle disabled on file input and camera icon
        fileUp.toggleAttribute('disabled');
        btnCamera.classList.toggle('d-none');

        // Show/Hide save buttons and hide the edit button
        saveContainer.classList.toggle('d-none');
        btnEdit.classList.toggle('d-none');
    }

    function loadImg(e) {
        if(e.target.files && e.target.files[0]) {
            const r = new FileReader();
            r.onload = function(ev){ document.getElementById('preview').src = ev.target.result; };
            r.readAsDataURL(e.target.files[0]);
        }
    }
    
    function updateBio() {
        const val = document.getElementById('bioInput').value;
        document.getElementById('bioDisplay').innerText = val ? `"${val}"` : "";
    }
</script>
@endsection