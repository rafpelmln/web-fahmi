@extends('layouts.app')

@section('title', 'Daftar Portfolio')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div>
                <h1 class="h4 mb-1 fw-bold">Manajemen Portfolio</h1>
                <p class="text-muted mb-0">Kelola seluruh data berdasarkan hak akses {{ $user->role }}.</p>
            </div>
            @can('create', App\Models\Portfolio::class)
                <a href="{{ route('portfolios.create') }}" class="btn btn-primary mt-3 mt-md-0">
                    <i class="fas fa-plus me-2"></i>Tambah Portfolio
                </a>
            @endcan
        </div>
    </div>

    <div class="col-12">
        <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">Total</small>
                        <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">Pending</small>
                        <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">Disetujui</small>
                        <h3 class="fw-bold mb-0 text-success">{{ $stats['approved'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">Ditolak</small>
                        <h3 class="fw-bold mb-0 text-danger">{{ $stats['rejected'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('portfolios.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted small mb-1">Pencarian</label>
                        <input type="text" name="search" class="form-control" placeholder="Judul atau nama siswa"
                               value="{{ request('search') }}" autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                            <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                            <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">Tipe</label>
                        <select name="type" class="form-select">
                            <option value="">Semua Tipe</option>
                            <option value="prestasi" @selected(request('type') === 'prestasi')>Prestasi</option>
                            <option value="karya" @selected(request('type') === 'karya')>Karya</option>
                            <option value="sertifikat" @selected(request('type') === 'sertifikat')>Sertifikat</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-filter me-2"></i> Terapkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if($portfolios->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Judul</th>
                                    <th>Siswa</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($portfolios as $portfolio)
                                    <tr>
                                        <td>{{ $loop->iteration + ($portfolios->firstItem() - 1) }}</td>
                                        <td class="fw-semibold">{{ $portfolio->title }}</td>
                                        <td>
                                            <div>{{ $portfolio->student->name }}</div>
                                            <small class="text-muted">{{ $portfolio->student->class }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary text-uppercase">{{ $portfolio->type }}</span>
                                        </td>
                                        <td>
                                            @if($portfolio->verified_status === 'pending')
                                                <span class="badge badge-pending"><i class="fas fa-clock me-1"></i>Pending</span>
                                            @elseif($portfolio->verified_status === 'approved')
                                                <span class="badge badge-approved"><i class="fas fa-check-circle me-1"></i>Disetujui</span>
                                            @else
                                                <span class="badge badge-rejected"><i class="fas fa-times-circle me-1"></i>Ditolak</span>
                                            @endif
                                        </td>
                                            <td>{{ $portfolio->created_at->translatedFormat('d M Y') }}</td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('portfolios.show', $portfolio) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @can('update', $portfolio)
                                                    <a href="{{ route('portfolios.edit', $portfolio) }}" class="btn btn-outline-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('verify', $portfolio)
                                                    <a href="{{ route('portfolios.show', ['portfolio' => $portfolio, 'tab' => 'verifikasi']) }}" class="btn btn-outline-success">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top">
                        {{ $portfolios->links() }}
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-folder-open fa-2x mb-3"></i>
                        <p class="mb-0">Belum ada data sesuai filter.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
