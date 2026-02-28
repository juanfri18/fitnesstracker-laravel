<div class="offcanvas offcanvas-start offcanvas-custom" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header offcanvas-header-custom">
        <h5 class="offcanvas-title fw-bold"><i class="fas fa-heartbeat me-2"></i>Menú Principal</h5>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-4">
        <a href="/" class="menu-link {{ request()->is('/') ? 'active' : '' }}"><i class="fas fa-home text-primary"></i> Inicio</a>
        <a href="/registro" class="menu-link {{ request()->is('registro') ? 'active' : '' }}"><i class="fas fa-plus-circle text-success"></i> Registrar</a>
        <a href="/estadisticas" class="menu-link {{ request()->is('estadisticas') ? 'active' : '' }}"><i class="fas fa-chart-line text-info"></i> Estadísticas</a>
        <a href="/perfil" class="menu-link {{ request()->is('perfil') ? 'active' : '' }}"><i class="fas fa-user-cog text-secondary"></i> Mi Perfil</a>
        
        <a href="#" class="menu-link logout mt-auto"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>
</div>
