<nav class="navbar navbar-custom sticky-top py-3">
    <div class="container-fluid px-4 align-items-center">
        <div class="d-flex align-items-center">
            <button class="btn-menu me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <i class="fas fa-bars fs-5"></i>
            </button>
            <a class="navbar-brand mb-0" href="/"><i class="fas fa-heartbeat me-2"></i>FitnessTracker</a>
        </div>
        <a href="/perfil" class="d-flex align-items-center text-white text-decoration-none" style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 20px; cursor: pointer;">
            @if(Auth::check() && Auth::user()->foto)
                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Avatar" class="rounded-circle me-2" style="width: 26px; height: 26px; object-fit: cover;">
            @else
                <i class="fas fa-user-circle fs-5 me-2"></i>
            @endif
            <span class="fw-bold d-none d-sm-inline">{{ Auth::check() ? Auth::user()->name : 'Invitado' }}</span>
        </a>
    </div>
</nav>
