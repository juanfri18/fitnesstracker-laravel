<div class="offcanvas offcanvas-start offcanvas-custom" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header offcanvas-header-custom">
        <h5 class="offcanvas-title fw-bold"><i class="fas fa-heartbeat me-2"></i>Menú Principal</h5>
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
