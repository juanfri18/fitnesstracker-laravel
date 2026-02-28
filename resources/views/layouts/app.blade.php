<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnessTracker - @yield('titulo', 'Inicio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-color: #2A5199; --bg-light: #f0f2f5; }
        body { background-color: var(--bg-light); font-family: 'Segoe UI', sans-serif; }
        .navbar-custom { background-color: var(--primary-color); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .navbar-brand { color: white !important; font-weight: 800; font-size: 1.4rem; letter-spacing: 0.5px;}
        .btn-menu { background: rgba(255,255,255,0.1); border: none; color: white; border-radius: 8px; padding: 8px 12px; transition: 0.3s; }
        .btn-menu:hover { background: rgba(255,255,255,0.2); transform: translateY(-1px); }
        .offcanvas-custom { border-radius: 0 20px 20px 0; border: none; box-shadow: 5px 0 25px rgba(0,0,0,0.15); width: 280px !important; }
        .offcanvas-header-custom { background: linear-gradient(135deg, var(--primary-color), #1e3c72); color: white; border-radius: 0 20px 0 0; padding: 1.5rem; }
        .menu-link { padding: 12px 20px; border-radius: 12px; margin-bottom: 8px; color: #495057; font-weight: 600; transition: all 0.3s ease; display: flex; align-items: center; text-decoration: none;}
        .menu-link i { font-size: 1.2rem; width: 30px; text-align: center; }
        .menu-link:hover { background-color: #f8f9fa; color: var(--primary-color); transform: translateX(5px); }
        .menu-link.active { background-color: rgba(42, 81, 153, 0.1); color: var(--primary-color); }
        .menu-link.logout { color: #dc3545; margin-top: auto; }
        .menu-link.logout:hover { background-color: rgba(220, 53, 69, 0.1); color: #c82333; }
        /* Permite inyectar estilos específicos de cada página */
        @yield('css_extra') 
    </style>
</head>
<body>

    @include('partials.navbar')
    @include('partials.sidebar')

    <main>
        @yield('contenido')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts_extra')
</body>
</html>