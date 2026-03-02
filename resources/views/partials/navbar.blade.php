<nav class="navbar navbar-custom sticky-top py-3">
    <div class="container-fluid px-4 align-items-center">
        <div class="d-flex align-items-center">
            <button class="btn-menu me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <i class="fas fa-bars fs-5"></i>
            </button>
            <a class="navbar-brand mb-0 d-flex align-items-center" href="/">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" style="height: 85px; width: auto; filter: drop-shadow(0px 0px 4px rgba(255, 255, 255, 0.8)) drop-shadow(0px 0px 1px rgba(255, 255, 255, 1));" class="me-1">
                <span class="d-flex align-items-center" style="font-family: 'Arial Black', 'Segoe UI', sans-serif; text-transform: uppercase;">
                    <span style="color: #ffffff; font-weight: 900; font-size: 1.5rem; letter-spacing: 2px; text-shadow: -2px 2px 0px rgba(255, 255, 255, 0.2), -4px 4px 0px rgba(255, 255, 255, 0.1);">SINERGY</span>
                    <span style="background: linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 900; font-size: 1.8rem; font-style: italic; margin-left: -2px; padding-right: 5px; filter: drop-shadow(-2px 2px 4px rgba(255, 65, 108, 0.8)) drop-shadow(-4px 4px 0px rgba(255, 65, 108, 0.4));">FIT</span>
                </span>
            </a>
        </div>
        <a href="/perfil" class="d-flex align-items-center text-white text-decoration-none" style="background: rgba(255,255,255,0.2); padding: 8px 18px; border-radius: 30px; cursor: pointer;">
            @if(Auth::check() && Auth::user()->foto)
                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Avatar" class="rounded-circle me-2 shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
            @else
                <i class="fas fa-user-circle fs-3 me-2"></i>
            @endif
            <span class="fw-bold d-none d-sm-inline" style="font-size: 1.15rem; letter-spacing: 0.5px;">{{ Auth::check() ? Auth::user()->name : 'Invitado' }}</span>
        </a>
    </div>
</nav>
