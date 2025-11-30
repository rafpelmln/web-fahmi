<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $user = auth()->user();
    @endphp

    <div class="py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-1">
                                <i class="fas fa-layer-group text-primary me-2"></i>Menu Utama
                            </h5>
                            <p class="text-muted mb-4">Semua fitur CRUD bisa diakses dari menu cepat di bawah ini.</p>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <a href="{{ route('portfolios.index') }}" class="text-decoration-none text-dark">
                                        <div class="p-3 border rounded-3 h-100 bg-white">
                                            <div class="d-flex align-items-start gap-3">
                                                <span class="badge bg-primary-subtle text-primary fs-4 p-3 rounded-3">
                                                    <i class="fas fa-folder-open"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-1">Daftar Portfolio</h6>
                                                    <p class="text-muted small mb-0">Lihat dan kelola seluruh data portfolio yang tersedia.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                @if($user && in_array($user->role, ['student', 'admin']))
                                    <div class="col-sm-6">
                                        <a href="{{ route('portfolios.create') }}" class="text-decoration-none text-dark">
                                            <div class="p-3 border rounded-3 h-100 bg-white">
                                                <div class="d-flex align-items-start gap-3">
                                                    <span class="badge bg-success-subtle text-success fs-4 p-3 rounded-3">
                                                        <i class="fas fa-plus"></i>
                                                    </span>
                                                    <div>
                                                        <h6 class="mb-1">Tambah Portfolio</h6>
                                                        <p class="text-muted small mb-0">Input data baru lengkap dengan file pendukung.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif

                                @if($user && in_array($user->role, ['teacher', 'admin']))
                                    <div class="col-sm-6">
                                        <a href="{{ route('portfolios.index', ['status' => 'pending']) }}" class="text-decoration-none text-dark">
                                            <div class="p-3 border rounded-3 h-100 bg-white">
                                                <div class="d-flex align-items-start gap-3">
                                                    <span class="badge bg-warning-subtle text-warning fs-4 p-3 rounded-3">
                                                        <i class="fas fa-check-double"></i>
                                                    </span>
                                                    <div>
                                                        <h6 class="mb-1">Verifikasi Pending</h6>
                                                        <p class="text-muted small mb-0">Periksa dan setujui portfolio yang menunggu review.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif

                                @if($user && $user->role === 'admin')
                                    <div class="col-sm-6">
                                        <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
                                            <div class="p-3 border rounded-3 h-100 bg-white">
                                                <div class="d-flex align-items-start gap-3">
                                                    <span class="badge bg-danger-subtle text-danger fs-4 p-3 rounded-3">
                                                        <i class="fas fa-users-cog"></i>
                                                    </span>
                                                    <div>
                                                        <h6 class="mb-1">Manajemen Users</h6>
                                                        <p class="text-muted small mb-0">Kelola akun admin, guru, maupun siswa.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif

                                <div class="col-sm-6">
                                    <a href="{{ route('profile.edit') }}" class="text-decoration-none text-dark">
                                        <div class="p-3 border rounded-3 h-100 bg-white">
                                            <div class="d-flex align-items-start gap-3">
                                                <span class="badge bg-info-subtle text-info fs-4 p-3 rounded-3">
                                                    <i class="fas fa-user-edit"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-1">Pengaturan Profil</h6>
                                                    <p class="text-muted small mb-0">Perbarui informasi akun dan ubah kata sandi.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white">
                            <strong>Status Login</strong>
                        </div>
                        <div class="card-body small text-muted">
                            <div class="mb-2">
                                <span class="text-dark d-block fw-semibold">{{ $user->name ?? 'Guest' }}</span>
                                <span class="badge bg-primary-subtle text-primary text-uppercase">{{ $user->role ?? '-' }}</span>
                            </div>
                            <dl class="row mb-0">
                                <dt class="col-6">Email</dt>
                                <dd class="col-6 text-end">{{ $user->email ?? 'n/a' }}</dd>
                                <dt class="col-6">Session ID</dt>
                                <dd class="col-6 text-end">{{ session()->getId() }}</dd>
                                <dt class="col-6">Auth ID</dt>
                                <dd class="col-6 text-end">{{ auth()->id() ?? 'guest' }}</dd>
                                <dt class="col-6">APP URL</dt>
                                <dd class="col-6 text-end">{{ config('app.url') }}</dd>
                            </dl>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <strong>Panduan Singkat</strong>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-circle text-success me-2"></i>
                                    Gunakan menu di kiri untuk berpindah antar CRUD page.
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-circle text-warning me-2"></i>
                                    Siswa hanya dapat mengubah portfolio milik sendiri yang masih pending.
                                </li>
                                <li>
                                    <i class="fas fa-circle text-info me-2"></i>
                                    Guru/Admin dapat memverifikasi portfolio melalui menu pending.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
