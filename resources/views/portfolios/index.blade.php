@extends('layouts.app')

@section('title', 'Daftar Portfolio')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-folder-open"></i> Daftar Portfolio
            </h2>
            @can('create', App\Models\Portfolio::class)
            <a href="{{ route('portfolios.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Portfolio Baru
            </a>
            @endcan
        </div>

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('portfolios.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari judul atau nama siswa..."
                               value="{{ request('search') }}" autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" @if(request('status') === 'pending') selected @endif>Pending</option>
                            <option value="approved" @if(request('status') === 'approved') selected @endif>Disetujui</option>
                            <option value="rejected" @if(request('status') === 'rejected') selected @endif>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-select">
                            <option value="">Semua Tipe</option>
                            <option value="prestasi" @if(request('type') === 'prestasi') selected @endif>Prestasi</option>
                            <option value="karya" @if(request('type') === 'karya') selected @endif>Karya</option>
                            <option value="sertifikat" @if(request('type') === 'sertifikat') selected @endif>Sertifikat</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Portfolio List -->
        @if($portfolios->count() > 0)
        <div class="row">
            @foreach($portfolios as $portfolio)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card portfolio-item h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">{{ $portfolio->title }}</h5>
                            @if($portfolio->type === 'prestasi')
                                <span class="badge bg-info"><i class="fas fa-trophy"></i> Prestasi</span>
                            @elseif($portfolio->type === 'karya')
                                <span class="badge bg-primary"><i class="fas fa-palette"></i> Karya</span>
                            @else
                                <span class="badge bg-success"><i class="fas fa-certificate"></i> Sertifikat</span>
                            @endif
                        </div>

                        <p class="text-muted mb-2">
                            <small>
                                <i class="fas fa-user"></i> 
                                {{ $portfolio->student->name }} ({{ $portfolio->student->class }})
                            </small>
                        </p>

                        <p class="card-text text-truncate" title="{{ $portfolio->description }}">
                            {{ Str::limit($portfolio->description, 100) }}
                        </p>

                        <div class="mb-3">
                            @if($portfolio->verified_status === 'pending')
                                <span class="badge badge-pending">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @elseif($portfolio->verified_status === 'approved')
                                <span class="badge badge-approved">
                                    <i class="fas fa-check-circle"></i> Disetujui
                                </span>
                            @else
                                <span class="badge badge-rejected">
                                    <i class="fas fa-times-circle"></i> Ditolak
                                </span>
                            @endif
                        </div>

                        <small class="text-muted d-block mb-3">
                            <i class="fas fa-calendar"></i> 
                            {{ $portfolio->created_at->locale('id')->translatedFormat('d F Y') }}
                        </small>

                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('portfolios.show', $portfolio) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                            @can('update', $portfolio)
                            <a href="{{ route('portfolios.edit', $portfolio) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @endcan
                            @can('verify', $portfolio)
                            <a href="{{ route('portfolios.show', $portfolio) }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-check"></i> Verifikasi
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $portfolios->links() }}
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i>
            <p class="mb-0">Tidak ada portfolio yang ditemukan.</p>
        </div>
        @endif
    </div>
</div>
@endsection
