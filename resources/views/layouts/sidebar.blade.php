@php
    $user = auth()->user();
@endphp

<aside class="app-sidebar d-none d-lg-flex flex-column">
    <a href="{{ route('dashboard') }}" class="brand text-decoration-none">
        <i class="fas fa-briefcase text-primary"></i>
        Portfolio System
    </a>

    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="fas fa-chart-pie me-2"></i> Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('portfolios.index') ? 'active' : '' }}" href="{{ route('portfolios.index') }}">
            <i class="fas fa-folder-open me-2"></i> Semua Portfolio
        </a>
        @if($user && in_array($user->role, ['student', 'admin']))
            <a class="nav-link {{ request()->routeIs('portfolios.create') ? 'active' : '' }}" href="{{ route('portfolios.create') }}">
                <i class="fas fa-plus-circle me-2"></i> Tambah Portfolio
            </a>
        @endif
        @if($user && in_array($user->role, ['teacher', 'admin']))
            <a class="nav-link {{ request()->routeIs('portfolios.index') && request('status') === 'pending' ? 'active' : '' }}" href="{{ route('portfolios.index', ['status' => 'pending']) }}">
                <i class="fas fa-clipboard-check me-2"></i> Verifikasi Pending
            </a>
        @endif
        @if($user && $user->role === 'admin')
            <a class="nav-link {{ request()->is('admin/*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users-cog me-2"></i> Manajemen Users
            </a>
        @endif
        <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
            <i class="fas fa-user-cog me-2"></i> Profil
        </a>
    </nav>

    <div class="mt-auto w-100">
        <small class="text-secondary d-block mb-2">Panduan Penilaian</small>
        <ul class="list-unstyled small text-secondary mb-3">
            <li><i class="fas fa-check-circle text-success me-2"></i> Tools & metode terlampir</li>
            <li><i class="fas fa-check-circle text-success me-2"></i> CRUD lengkap tiap role</li>
            <li><i class="fas fa-check-circle text-success me-2"></i> Debug & dokumentasi</li>
        </ul>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger w-100">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>
</aside>
