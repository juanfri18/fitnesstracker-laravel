<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnessTracker - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-color: #2A5199; --bg-light: #f0f2f5; }
        body { background-color: var(--bg-light); font-family: 'Segoe UI', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; }
        .login-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 450px; padding: 2rem; background: white; }
        .btn-login { background-color: var(--primary-color); color: white; border-radius: 25px; font-weight: 600; padding: 12px; width: 100%; }
        .btn-login:hover { background-color: #1e3c72; color: white; }
        .icon-container { width: 70px; height: 70px; background: rgba(42, 81, 153, 0.1); color: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem auto; }
    </style>
</head>
<body>

    <div class="login-card text-center">
        <div class="icon-container">
            <i class="fas fa-user-plus"></i>
        </div>
        <h3 class="fw-bold mb-1" style="color: var(--primary-color);">Crear Cuenta</h3>
        <p class="text-muted mb-4 small">Únete a FitnessTracker y empieza a mejorar.</p>

        @if ($errors->any())
            <div class="alert alert-danger text-start small">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/registro-usuario" method="POST" class="text-start">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Nombre</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" name="name" class="form-control border-start-0 ps-0" placeholder="Tu nombre" value="{{ old('name') }}" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="atleta@ejemplo.com" value="{{ old('email') }}" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Mínimo 8 caracteres" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">Confirmar Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" placeholder="Repite la contraseña" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login shadow-sm mb-3">Registrarse</button>

            <div class="text-center mt-3">
                <small class="text-muted">¿Ya tienes cuenta? <a href="/login" style="color: var(--primary-color); text-decoration: none; font-weight: bold;">Inicia Sesión</a></small>
            </div>
        </form>
    </div>

</body>
</html>
