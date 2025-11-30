@extends('layouts.app')

@section('title', 'Buat Portfolio Baru')

@section('content')
@php
    $user = auth()->user();
@endphp

<div class="row g-4 align-items-start">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header d-flex justify-content-between align-items-start">
                <div>
                    <span class="step-pill mb-2 d-inline-flex align-items-center gap-2">
                        <i class="fas fa-clipboard-list"></i> Langkah 1
                    </span>
                    <h3 class="card-title mt-2">Buat Portfolio Baru</h3>
                    <p class="text-muted mb-0">Lengkapi data berikut agar portfolio siap diverifikasi guru.</p>
                </div>
                <span class="badge bg-primary-subtle text-primary text-uppercase">Draft</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('portfolios.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf

                    @if($user->role === 'admin')
                        <div class="mb-4">
                            <label for="student_id" class="form-label fw-semibold">Pilih Siswa <span class="text-danger">*</span></label>
                            <select id="student_id" name="student_id" class="form-select form-select-lg @error('student_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($students as $s)
                                    <option value="{{ $s->id }}" @selected(old('student_id') == $s->id)>
                                        {{ $s->name }} @if($s->class) ({{ $s->class }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">Admin dapat memilih siswa mana pun yang terdaftar.</small>
                            @error('student_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="student_id" value="{{ old('student_id', $user->id) }}">
                        <div class="alert alert-info mb-4">
                            <strong>Hi {{ $user->name }}!</strong> Portfolio ini otomatis tercatat sebagai milikmu.
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="title" class="form-label fw-semibold">Judul Portfolio <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}"
                               placeholder="Contoh: Juara 1 Lomba Sains" required minlength="3" maxlength="255">
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
                                <option value="prestasi" @selected(old('type') === 'prestasi')>Prestasi</option>
                                <option value="karya" @selected(old('type') === 'karya')>Karya</option>
                                <option value="sertifikat" @selected(old('type') === 'sertifikat')>Sertifikat</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Otomatis</label>
                            <div class="status-chip pending">
                                <i class="fas fa-clock"></i> Pending (menunggu guru)
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="5"
                                  placeholder="Ceritakan detail kegiatan atau prestasi..." required>{{ old('description') }}</textarea>
                        <small class="text-muted d-block mt-2">Minimal 10 karakter, maksimal 5000 karakter.</small>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="file" class="form-label fw-semibold">File Pendukung (PDF/JPG/PNG) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white"><i class="fas fa-paperclip"></i></span>
                            <input type="file" class="form-control @error('file') is-invalid @enderror"
                                   id="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>
                        <small class="text-muted d-block mt-2">Format: PDF, JPG, PNG. Ukuran maksimal 5MB.</small>
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

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Simpan Portfolio
                        </button>
                        <a href="{{ route('portfolios.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card info-panel">
            <div class="card-header bg-primary text-white d-flex align-items-center gap-2">
                <i class="fas fa-lightbulb"></i> Petunjuk Singkat
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Checklist sebelum submit:</h6>
                <ul class="list-unstyled small mb-4">
                    <li><i class="fas fa-check text-success me-2"></i>Judul jelas & relevan</li>
                    <li><i class="fas fa-check text-success me-2"></i>Deskripsi detail kegiatan</li>
                    <li><i class="fas fa-check text-success me-2"></i>Pilih jenis portfolio yang sesuai</li>
                    <li><i class="fas fa-check text-success me-2"></i>Pastikan file terbaca (max 5MB)</li>
                </ul>
                <h6 class="fw-bold">Status Verifikasi:</h6>
                <ul class="status-legend list-unstyled small mb-0">
                    <li><span class="status-chip pending"><i class="fas fa-clock"></i> Pending</span> Menunggu guru</li>
                    <li><span class="status-chip approved"><i class="fas fa-check-circle"></i> Approved</span> Sudah disetujui</li>
                    <li><span class="status-chip rejected"><i class="fas fa-times-circle"></i> Rejected</span> Perlu revisi</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview file yang diupload
    document.getElementById('file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const fileName = file.name;
            const fileSize = (file.size / 1024).toFixed(2); // Convert to KB
            
            document.getElementById('fileName').textContent = fileName;
            document.getElementById('fileSize').textContent = fileSize + ' KB';
            document.getElementById('filePreview').style.display = 'block';
        }
    });
</script>
@endpush
@endsection
