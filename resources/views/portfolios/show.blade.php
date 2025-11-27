@extends('layouts.app')

@section('title', $portfolio->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-folder"></i> {{ $portfolio->title }}
            </h2>
            <div>
                @if($portfolio->type === 'prestasi')
                    <span class="badge bg-info"><i class="fas fa-trophy"></i> Prestasi</span>
                @elseif($portfolio->type === 'karya')
                    <span class="badge bg-primary"><i class="fas fa-palette"></i> Karya</span>
                @else
                    <span class="badge bg-success"><i class="fas fa-certificate"></i> Sertifikat</span>
                @endif
            </div>
        </div>

        <!-- Portfolio Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Portfolio</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Nama Siswa</p>
                        <h6>{{ $portfolio->student->name }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Kelas</p>
                        <h6>{{ $portfolio->student->class }}</h6>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">NIS</p>
                        <h6>{{ $portfolio->student->nis }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Status Verifikasi</p>
                        <div>
                            @if($portfolio->verified_status === 'pending')
                                <span class="badge badge-pending"><i class="fas fa-clock"></i> Pending</span>
                            @elseif($portfolio->verified_status === 'approved')
                                <span class="badge badge-approved"><i class="fas fa-check-circle"></i> Disetujui</span>
                            @else
                                <span class="badge badge-rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <p class="text-muted mb-1">Deskripsi</p>
                    <p class="lh-lg">
                        {{ nl2br(e($portfolio->description)) }}
                    </p>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Dibuat Pada</p>
                        <p>{{ $portfolio->created_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Diperbarui Pada</p>
                        <p>{{ $portfolio->updated_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                    </div>
                </div>

                @if($portfolio->verified_at)
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Diverifikasi Oleh</p>
                        <p>{{ $portfolio->verifiedByUser->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Tanggal Verifikasi</p>
                        <p>{{ $portfolio->verified_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- File Display -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file"></i> File Portfolio
                </h5>
            </div>
            <div class="card-body text-center">
                <p class="mb-3">
                    <i class="fas fa-file-download fa-3x text-primary"></i>
                </p>
                <h6>{{ $portfolio->file_name }}</h6>
                <p class="text-muted mb-3">
                    Size: {{ number_format(Storage::disk('public')->size($portfolio->file_path) / 1024, 2) }} KB
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ $portfolio->file_url }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-external-link-alt"></i> Buka File
                    </a>
                    <a href="{{ $portfolio->file_url }}" download class="btn btn-outline-primary">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
        </div>

        <!-- Verification Section (for teachers/admin) -->
        @can('verify', $portfolio)
        <div class="card mb-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle"></i> Verifikasi Portfolio
                </h5>
            </div>
            <div class="card-body">
                @if($portfolio->verified_status === 'pending')
                <p class="text-muted mb-3">
                    Silakan verifikasi portfolio ini. Siswa akan menerima notifikasi atas keputusan Anda.
                </p>

                <form action="{{ route('portfolios.verify', $portfolio) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="verified_status" class="form-label">Status Verifikasi <span class="text-danger">*</span></label>
                        <select class="form-select @error('verified_status') is-invalid @enderror" 
                                id="verified_status" name="verified_status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="approved">
                                <i class="fas fa-check-circle"></i> Setujui
                            </option>
                            <option value="rejected">
                                <i class="fas fa-times-circle"></i> Tolak
                            </option>
                        </select>
                        @error('verified_status')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="verification_notes" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control @error('verification_notes') is-invalid @enderror" 
                                  id="verification_notes" name="verification_notes" rows="3" 
                                  placeholder="Masukkan catatan atau alasan verifikasi (opsional)"
                                  maxlength="1000"></textarea>
                        @error('verification_notes')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Verifikasi Sekarang
                        </button>
                        <a href="{{ route('portfolios.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
                @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i>
                    Portfolio ini sudah diverifikasi dengan status 
                    <strong>{{ $portfolio->verified_status === 'approved' ? 'Disetujui' : 'Ditolak' }}</strong>
                    oleh {{ $portfolio->verifiedByUser->name ?? 'Admin' }} 
                    pada {{ $portfolio->verified_at->locale('id')->translatedFormat('d F Y H:i') }}.
                </div>
                @endif
            </div>
        </div>
        @endcan
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Action Buttons -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                @can('update', $portfolio)
                <a href="{{ route('portfolios.edit', $portfolio) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endcan

                @can('delete', $portfolio)
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash"></i> Hapus
                </button>
                @endcan

                <a href="{{ route('portfolios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Timeline</h5>
            </div>
            <div class="card-body small">
                <div class="timeline">
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <p class="text-muted mb-1">Dibuat</p>
                            <p class="mb-0">
                                {{ $portfolio->created_at->locale('id')->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>
                    </div>

                    @if($portfolio->updated_at && $portfolio->updated_at !== $portfolio->created_at)
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <p class="text-muted mb-1">Diperbarui</p>
                            <p class="mb-0">
                                {{ $portfolio->updated_at->locale('id')->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>
                    </div>
                    @endif

                    @if($portfolio->verified_at)
                    <div class="timeline-item">
                        <div class="timeline-marker @if($portfolio->verified_status === 'approved') bg-success @else bg-danger @endif"></div>
                        <div class="timeline-content">
                            <p class="text-muted mb-1">
                                {{ $portfolio->verified_status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                            </p>
                            <p class="mb-0">
                                {{ $portfolio->verified_at->locale('id')->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>
                    </div>
                    @else
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <p class="text-muted mb-1">Menunggu Verifikasi</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
@can('delete', $portfolio)
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash"></i> Hapus Portfolio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus portfolio <strong>"{{ $portfolio->title }}"</strong>?</p>
                <p class="text-danger mb-0">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Tindakan ini tidak dapat dibatalkan. File juga akan dihapus dari server.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('portfolios.destroy', $portfolio) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e0e0e0;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-marker {
        position: absolute;
        left: -15px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #e0e0e0;
    }
</style>
@endpush
@endsection
