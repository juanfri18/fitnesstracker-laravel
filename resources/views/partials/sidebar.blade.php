<div class="offcanvas offcanvas-start offcanvas-custom" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header offcanvas-header-custom">
        <h5 class="offcanvas-title fw-bold d-flex align-items-center">
            <img src="{{ asset('images/logo.webp') }}" alt="Logo" style="height: 45px; width: auto; filter: drop-shadow(0px 0px 3px rgba(255, 255, 255, 0.9)) drop-shadow(0px 0px 1px rgba(255, 255, 255, 1));" class="me-1">
            <span class="d-flex align-items-center" style="font-family: 'Arial Black', 'Segoe UI', sans-serif; text-transform: uppercase;">
                <span style="color: #ffffff; font-weight: 900; font-size: 1.4rem; letter-spacing: 2px; text-shadow: -2px 2px 0px rgba(255, 255, 255, 0.2), -4px 4px 0px rgba(255, 255, 255, 0.1);">SINERGY</span>
                <span style="background: linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 900; font-size: 1.7rem; font-style: italic; margin-left: -2px; padding-right: 5px; filter: drop-shadow(-2px 2px 4px rgba(255, 65, 108, 0.8)) drop-shadow(-4px 4px 0px rgba(255, 65, 108, 0.4));">FIT</span>
            </span>
        </h5>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-4">
        @auth
            <a href="/" class="menu-link {{ request()->is('/') ? 'active' : '' }}"><i class="fas fa-home text-primary"></i> Inicio</a>
            <a href="/registro" class="menu-link {{ request()->is('registro') ? 'active' : '' }}"><i class="fas fa-plus-circle text-success"></i> Registrar</a>
            <a href="/estadisticas" class="menu-link {{ request()->is('estadisticas') ? 'active' : '' }}"><i class="fas fa-chart-line text-info"></i> Estadísticas</a>
            <a href="/objetivos" class="menu-link {{ request()->is('objetivos') ? 'active' : '' }}"><i class="fas fa-bullseye text-warning"></i> Mis Metas</a>
            <a href="/calendario" class="menu-link {{ request()->is('calendario') ? 'active' : '' }}"><i class="far fa-calendar-alt text-danger"></i> Calendario</a>
            <a href="/perfil" class="menu-link {{ request()->is('perfil') ? 'active' : '' }}"><i class="fas fa-user-cog text-secondary"></i> Mi Perfil</a>
            
            <form action="/logout" method="POST" class="mt-auto w-100">
                @csrf
                <button type="submit" class="menu-link logout w-100 text-start bg-transparent border-0" style="cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>
            </form>
        @else
            <a href="/login" class="menu-link {{ request()->is('login') ? 'active' : '' }}"><i class="fas fa-sign-in-alt text-primary"></i> Iniciar Sesión</a>
            <a href="/registro-usuario" class="menu-link {{ request()->is('registro-usuario') ? 'active' : '' }}"><i class="fas fa-user-plus text-success"></i> Crear Cuenta</a>
        @endauth
    </div>
</div>
