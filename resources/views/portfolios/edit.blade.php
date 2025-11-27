@extends('layouts.app')

@section('title', 'Edit Portfolio: ' . $portfolio->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <h2 class="mb-4">
            <i class="fas fa-edit"></i> Edit Portfolio
        </h2>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('portfolios.update', $portfolio) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Portfolio <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $portfolio->title) }}" 
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
                            <option value="prestasi" @if(old('type', $portfolio->type) === 'prestasi') selected @endif>
                                <i class="fas fa-trophy"></i> Prestasi
                            </option>
                            <option value="karya" @if(old('type', $portfolio->type) === 'karya') selected @endif>
                                <i class="fas fa-palette"></i> Karya
                            </option>
                            <option value="sertifikat" @if(old('type', $portfolio->type) === 'sertifikat') selected @endif>
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
                                  required minlength="10" maxlength="5000">{{ old('description', $portfolio->description) }}</textarea>
                        <small class="form-text text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i> Minimal 10 karakter, maksimal 5000 karakter
                        </small>
                        @error('description')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Current File -->
                    <div class="mb-3">
                        <label class="form-label">File Saat Ini</label>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">
                                    <i class="fas fa-file"></i> 
                                    <a href="{{ $portfolio->file_url }}" target="_blank" class="text-decoration-none">
                                        {{ $portfolio->file_name }}
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        Uploaded: {{ $portfolio->created_at->locale('id')->translatedFormat('d F Y H:i') }}
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- New File Upload -->
                    <div class="mb-3">
                        <label for="file" class="form-label">Ganti File (PDF/JPG/PNG) <span class="text-muted">(Opsional)</span></label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                   id="file" name="file" accept=".pdf,.jpg,.jpeg,.png">
                            <span class="input-group-text">
                                <i class="fas fa-paperclip"></i>
                            </span>
                        </div>
                        <small class="form-text text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i> Kosongkan jika tidak ingin mengubah file | Format: PDF, JPG, PNG | Ukuran maksimal: 5MB
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

                    @if($portfolio->verified_status !== 'pending')
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian!</strong> Portfolio ini sudah {{ $portfolio->verified_status === 'approved' ? 'disetujui' : 'ditolak' }}. 
                        Jika Anda melakukan perubahan, status akan kembali menjadi pending.
                    </div>
                    @endif

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('portfolios.show', $portfolio) }}" class="btn btn-secondary">
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
            <div class="card-header bg-info text-white">
                <i class="fas fa-info-circle"></i> Informasi
            </div>
            <div class="card-body small">
                <p>
                    <strong>Status:</strong><br>
                    @if($portfolio->verified_status === 'pending')
                        <span class="badge badge-pending">Pending - Menunggu Verifikasi</span>
                    @elseif($portfolio->verified_status === 'approved')
                        <span class="badge badge-approved">Approved - Sudah Disetujui</span>
                    @else
                        <span class="badge badge-rejected">Rejected - Ditolak</span>
                    @endif
                </p>

                <hr>

                <p>
                    <strong>Dibuat:</strong><br>
                    {{ $portfolio->created_at->locale('id')->translatedFormat('d F Y H:i') }}
                </p>

                <p>
                    <strong>Diperbarui:</strong><br>
                    {{ $portfolio->updated_at->locale('id')->translatedFormat('d F Y H:i') }}
                </p>

                @if($portfolio->verified_at)
                <p>
                    <strong>Diverifikasi:</strong><br>
                    {{ $portfolio->verified_at->locale('id')->translatedFormat('d F Y H:i') }}
                </p>
                @endif
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
