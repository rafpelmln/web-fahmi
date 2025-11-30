@extends('layouts.app')

@section('title', 'Edit Portfolio: ' . $portfolio->title)

@section('content')
<div class="row g-4 align-items-start">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header d-flex justify-content-between align-items-start">
                <div>
                    <span class="step-pill mb-2 d-inline-flex align-items-center gap-2">
                        <i class="fas fa-pen"></i> Update Data
                    </span>
                    <h3 class="card-title mt-2">Edit Portfolio</h3>
                    <p class="text-muted mb-0">Perbarui informasi dan file pendukung untuk mempercepat verifikasi.</p>
                </div>
                <div class="status-chip {{ $portfolio->verified_status }}">
                    <i class="fas fa-circle"></i> {{ $portfolio->verified_status }}
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('portfolios.update', $portfolio) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="form-label fw-semibold">Judul Portfolio <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title', $portfolio->title) }}" required minlength="3" maxlength="255">
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="type" class="form-label fw-semibold">Jenis Portfolio <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg @error('type') is-invalid @enderror"
                                    id="type" name="type" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="prestasi" @selected(old('type', $portfolio->type) === 'prestasi')>Prestasi</option>
                                <option value="karya" @selected(old('type', $portfolio->type) === 'karya')>Karya</option>
                                <option value="sertifikat" @selected(old('type', $portfolio->type) === 'sertifikat')>Sertifikat</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Terakhir Diverifikasi</label>
                            <p class="mb-0 fw-semibold">
                                {{ $portfolio->verified_at ? $portfolio->verified_at->locale('id')->translatedFormat('d M Y H:i') : 'Belum diverifikasi' }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="5" required>{{ old('description', $portfolio->description) }}</textarea>
                        <small class="text-muted d-block mt-2">Minimal 10 karakter, maksimal 5000 karakter.</small>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">File Saat Ini</label>
                        <div class="alert alert-light border d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $portfolio->file_name }}</strong>
                                <div class="text-muted small">Diunggah {{ $portfolio->created_at->locale('id')->translatedFormat('d M Y H:i') }}</div>
                            </div>
                            <a href="{{ $portfolio->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Lihat
                            </a>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="file" class="form-label fw-semibold">Ganti File (opsional)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white"><i class="fas fa-upload"></i></span>
                            <input type="file" class="form-control @error('file') is-invalid @enderror"
                                   id="file" name="file" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <small class="text-muted d-block mt-2">Biarkan kosong jika tidak ingin mengubah file.</small>
                        @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="filePreview" class="mb-4" style="display: none;">
                        <div class="alert alert-secondary d-flex justify-content-between align-items-center">
                            <div>
                                <strong id="fileName"></strong>
                                <div class="text-muted small" id="fileSize"></div>
                            </div>
                            <i class="fas fa-file"></i>
                        </div>
                    </div>

                    @if($portfolio->verified_status !== 'pending')
                        <div class="alert alert-warning mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Mengubah data akan mengembalikan status ke <strong>pending</strong> agar guru dapat meninjau ulang.
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('portfolios.show', $portfolio) }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card info-panel mb-4">
            <div class="card-header bg-info text-white">
                <i class="fas fa-info-circle me-2"></i> Ringkasan Status
            </div>
            <div class="card-body detail-list">
                <dl class="mb-0">
                    <dt>Status Saat Ini</dt>
                    <dd class="status-chip {{ $portfolio->verified_status }}">
                        <i class="fas fa-circle"></i> {{ $portfolio->verified_status }}
                    </dd>

                    <dt>Dibuat</dt>
                    <dd>{{ $portfolio->created_at->locale('id')->translatedFormat('d M Y H:i') }}</dd>

                    <dt>Diperbarui</dt>
                    <dd>{{ $portfolio->updated_at->locale('id')->translatedFormat('d M Y H:i') }}</dd>

                    @if($portfolio->verified_at)
                        <dt>Diverifikasi</dt>
                        <dd>{{ $portfolio->verified_at->locale('id')->translatedFormat('d M Y H:i') }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(2) + ' KB';
            document.getElementById('filePreview').style.display = 'block';
        }
    });
</script>
@endpush
@endsection
