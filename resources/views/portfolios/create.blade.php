@extends('layouts.app')

@section('title', 'Buat Portfolio Baru')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h2 class="mb-4">
            <i class="fas fa-plus-circle"></i> Buat Portfolio Baru
        </h2>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('portfolios.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf

                    <!-- Student (pilih dari akun siswa terdaftar) -->
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                        <select id="student_id" name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($students as $s)
                                <option value="{{ $s->id }}" @if(old('student_id') == $s->id) selected @endif>
                                    {{ $s->name }} @if($s->email) &middot; <small class="text-muted">{{ $s->email }}</small> @endif
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Portfolio <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" 
                               placeholder="Masukkan judul portfolio" required minlength="3" maxlength="255">
                        @error('title')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Portfolio <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" name="type" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="prestasi" @if(old('type') === 'prestasi') selected @endif>
                                <i class="fas fa-trophy"></i> Prestasi
                            </option>
                            <option value="karya" @if(old('type') === 'karya') selected @endif>
                                <i class="fas fa-palette"></i> Karya
                            </option>
                            <option value="sertifikat" @if(old('type') === 'sertifikat') selected @endif>
                                <i class="fas fa-certificate"></i> Sertifikat
                            </option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="5" 
                                  placeholder="Masukkan deskripsi lengkap portfolio" 
                                  required minlength="10" maxlength="5000">{{ old('description') }}</textarea>
                        <small class="form-text text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i> Minimal 10 karakter, maksimal 5000 karakter
                        </small>
                        @error('description')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="file" class="form-label">File (PDF/JPG/PNG) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                   id="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
                            <span class="input-group-text">
                                <i class="fas fa-paperclip"></i>
                            </span>
                        </div>
                        <small class="form-text text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i> Format: PDF, JPG, PNG | Ukuran maksimal: 5MB
                        </small>
                        @error('file')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- File Preview -->
                    <div id="filePreview" class="mb-3" style="display: none;">
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">
                                    <i class="fas fa-file"></i> <strong id="fileName"></strong>
                                    <br>
                                    <small id="fileSize" class="text-muted"></small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Portfolio
                        </button>
                        <a href="{{ route('portfolios.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-lightbulb"></i> Petunjuk
            </div>
            <div class="card-body">
                <h6>Persyaratan Portfolio:</h6>
                <ul class="small">
                    <li>Judul minimal 3 karakter</li>
                    <li>Deskripsi detail dan jelas</li>
                    <li>Pilih jenis portfolio yang sesuai</li>
                    <li>Upload file dengan format yang benar</li>
                    <li>Ukuran file tidak lebih dari 5MB</li>
                </ul>

                <hr>

                <h6>Status Verifikasi:</h6>
                <ul class="small">
                    <li><span class="badge badge-pending">Pending</span> - Menunggu verifikasi</li>
                    <li><span class="badge badge-approved">Approved</span> - Sudah disetujui</li>
                    <li><span class="badge badge-rejected">Rejected</span> - Ditolak oleh guru</li>
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
