<nav class="topbar navbar navbar-expand-lg px-3">
    <a class="navbar-brand fw-bold d-lg-none" href="{{ route('dashboard') }}">
        <i class="fas fa-briefcase me-2 text-primary"></i> Portfolio System
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mobileNav">
        <ul class="navbar-nav me-auto mt-3 mt-lg-0 d-lg-none">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('portfolios.index') }}">Portfolios</a></li>
            @auth
                @if(auth()->user()->role === 'student' || auth()->user()->role === 'admin')
                    <li class="nav-item"><a class="nav-link" href="{{ route('portfolios.create') }}">Tambah Portfolio</a></li>
                @endif
                @if(auth()->user()->role === 'teacher' || auth()->user()->role === 'admin')
                    <li class="nav-item"><a class="nav-link" href="{{ route('portfolios.index', ['status' => 'pending']) }}">Verifikasi</a></li>
                @endif
                @if(auth()->user()->role === 'admin')
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Manajemen Users</a></li>
                @endif
            @endauth
        </ul>

        <ul class="navbar-nav ms-auto align-items-center">
            @auth
                <li class="nav-item me-3 text-muted small d-none d-lg-block">
                    <span class="d-block fw-semibold">{{ auth()->user()->name }}</span>
                    <span class="text-uppercase">{{ auth()->user()->role }}</span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                        <span class="avatar rounded-circle bg-primary bg-opacity-10 text-primary p-2">
                            <i class="fas fa-user"></i>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-cog me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
            @endauth
        </ul>
    </div>
</nav>
