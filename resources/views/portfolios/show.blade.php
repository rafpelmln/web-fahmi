@extends('layouts.app')

@section('title', $portfolio->title)

@section('content')
@php
    $statusClass = match($portfolio->verified_status) {
        'approved' => 'approved',
        'rejected' => 'rejected',
        default => 'pending',
    };
@endphp

<div class="row g-4 align-items-start">
    <div class="col-lg-8">
        <div class="card form-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-start">
                <div>
                    <span class="step-pill mb-2 d-inline-flex align-items-center gap-2">
                        <i class="fas fa-id-badge"></i> Portfolio Siswa
                    </span>
                    <h3 class="card-title mt-2">{{ $portfolio->title }}</h3>
                    <p class="text-muted mb-0">Diajukan oleh {{ $portfolio->student->name }} ({{ $portfolio->student->class }})</p>
                </div>
                <span class="status-chip {{ $statusClass }}">
                    <i class="fas fa-circle"></i> {{ $portfolio->verified_status }}
                </span>
            </div>
            <div class="card-body p-4">
                <dl class="detail-list mb-0">
                    <div class="row">
                        <div class="col-md-6">
                            <dt>Nama Siswa</dt>
                            <dd>{{ $portfolio->student->name }}</dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Kelas</dt>
                            <dd>{{ $portfolio->student->class }}</dd>
                        </div>
                        <div class="col-md-6">
                            <dt>NIS</dt>
                            <dd>{{ $portfolio->student->nis }}</dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Jenis Portfolio</dt>
                            <dd class="text-uppercase">{{ $portfolio->type }}</dd>
                        </div>
                    </div>
                </dl>
                <hr>
                <div class="mb-3">
                    <h5 class="fw-bold">Deskripsi</h5>
                    <p class="text-muted">{!! nl2br(e($portfolio->description)) !!}</p>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <div class="text-muted small">Dibuat</div>
                            <div class="fw-semibold">{{ $portfolio->created_at->locale('id')->translatedFormat('d M Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <div class="text-muted small">Diperbarui</div>
                            <div class="fw-semibold">{{ $portfolio->updated_at->locale('id')->translatedFormat('d M Y H:i') }}</div>
                        </div>
                    </div>
                    @if($portfolio->verified_at)
                        <div class="col-md-6">
                            <div class="bg-light rounded-3 p-3">
                                <div class="text-muted small">Diverifikasi oleh</div>
                                <div class="fw-semibold">{{ $portfolio->verifiedByUser->name ?? 'Admin' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light rounded-3 p-3">
                                <div class="text-muted small">Tanggal Verifikasi</div>
                                <div class="fw-semibold">{{ $portfolio->verified_at->locale('id')->translatedFormat('d M Y H:i') }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between">
                <h5 class="mb-0"><i class="fas fa-file me-2"></i>File Portfolio</h5>
                <span class="badge bg-secondary text-uppercase">{{ $portfolio->type }}</span>
            </div>
            <div class="card-body text-center">
                <p class="mb-3 text-muted">Pastikan file dapat dibuka agar proses verifikasi lancar.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ $portfolio->file_url }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-external-link-alt me-2"></i>Buka File
                    </a>
                    <a href="{{ $portfolio->file_url }}" download class="btn btn-outline-primary">
                        <i class="fas fa-download me-2"></i>Unduh
                    </a>
                </div>
            </div>
        </div>

        @can('verify', $portfolio)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Verifikasi Portfolio</h5>
                </div>
                <div class="card-body">
                    @if($portfolio->verified_status === 'pending')
                        <p class="text-muted">Pilih status verifikasi dan tambahkan catatan apabila diperlukan.</p>
                        <form action="{{ route('portfolios.verify', $portfolio) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="verified_status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('verified_status') is-invalid @enderror" name="verified_status" id="verified_status" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="approved">Setujui</option>
                                    <option value="rejected">Tolak</option>
                                </select>
                                @error('verified_status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="verification_notes" class="form-label">Catatan</label>
                                <textarea class="form-control @error('verification_notes') is-invalid @enderror" name="verification_notes" id="verification_notes" rows="3"></textarea>
                                @error('verification_notes')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-warning"><i class="fas fa-save me-2"></i>Verifikasi Sekarang</button>
                        </form>
                    @else
                        <div class="alert alert-info mb-0">
                            Portfolio ini sudah diverifikasi sebagai <strong>{{ $portfolio->verified_status }}</strong> pada {{ $portfolio->verified_at->locale('id')->translatedFormat('d M Y H:i') }}.
                        </div>
                    @endif
                </div>
            </div>
        @endcan
    </div>

    <div class="col-lg-4">
        <div class="card info-panel mb-4">
            <div class="card-header bg-white d-flex justify-content-between">
                <h5 class="mb-0">Aksi</h5>
                <i class="fas fa-sliders-h text-muted"></i>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                @can('update', $portfolio)
                    <a class="btn btn-warning" href="{{ route('portfolios.edit', $portfolio) }}"><i class="fas fa-edit me-2"></i>Edit</a>
                @endcan
                @can('delete', $portfolio)
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-2"></i>Hapus
                    </button>
                @endcan
                <a href="{{ route('portfolios.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-stream me-2"></i>Timeline</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <strong>Dibuat</strong>
                        <div class="text-muted">{{ $portfolio->created_at->locale('id')->translatedFormat('d M Y H:i') }}</div>
                    </li>
                    @if($portfolio->updated_at && $portfolio->updated_at->ne($portfolio->created_at))
                        <li class="mb-3">
                            <strong>Diperbarui</strong>
                            <div class="text-muted">{{ $portfolio->updated_at->locale('id')->translatedFormat('d M Y H:i') }}</div>
                        </li>
                    @endif
                    @if($portfolio->verified_at)
                        <li>
                            <strong>{{ $portfolio->verified_status === 'approved' ? 'Disetujui' : 'Ditolak' }}</strong>
                            <div class="text-muted">{{ $portfolio->verified_at->locale('id')->translatedFormat('d M Y H:i') }}</div>
                        </li>
                    @else
                        <li>
                            <strong>Menunggu Verifikasi</strong>
                            <div class="text-muted">Belum ada keputusan guru</div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

@can('delete', $portfolio)
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Hapus Portfolio</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Anda yakin ingin menghapus <strong>{{ $portfolio->title }}</strong>? File akan ikut terhapus dari server.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('portfolios.destroy', $portfolio) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection
