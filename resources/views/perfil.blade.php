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
            <div class="card card-profile shadow-sm bg-white">
                <div class="profile-header"></div>
                <div class="card-body p-4">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="avatar-container mb-4">
                            <img src="https://via.placeholder.com/120?text=Usuario" id="preview" class="profile-img">
                            <input type="file" name="foto" id="fileUp" hidden onchange="loadImg(event)" accept="image/*">
                            <label for="fileUp" class="btn-camera" title="Cambiar foto"><i class="fas fa-camera text-primary"></i></label>
                            <h3 class="mt-2 fw-bold mb-0">{{ $user['nombre'] }} {{ $user['apellidos'] }}</h3>
                            <p class="text-muted fst-italic" id="bioDisplay">"{{ $user['biografia'] }}"</p>
                        </div>

                        <h5 class="section-title"><i class="fas fa-user me-2"></i>Datos Personales</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="{{ $user['nombre'] }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" value="{{ $user['apellidos'] }}" required>
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold">Biografía / Estado</label>
                                <input type="text" name="biografia" class="form-control" id="bioInput" value="{{ $user['biografia'] }}" oninput="updateBio()">
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-save shadow-sm">Guardar Cambios</button>
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