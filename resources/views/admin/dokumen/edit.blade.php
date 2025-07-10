@extends('layouts.admin')

@section('title', 'Edit Dokumen')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Dokumen</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.dokumen.index') }}">Dokumen</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Form Edit Dokumen
        </div>
        <div class="card-body">
            <form action="{{ route('admin.dokumen.update', $dokumen->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                   id="judul" name="judul" value="{{ old('judul', $dokumen->judul ?? 'Peraturan Inspektorat Papua Tengah 2024') }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="peraturan" {{ old('kategori', $dokumen->kategori ?? 'peraturan') == 'peraturan' ? 'selected' : '' }}>Peraturan</option>
                                <option value="panduan" {{ old('kategori', $dokumen->kategori ?? '') == 'panduan' ? 'selected' : '' }}>Panduan</option>
                                <option value="laporan" {{ old('kategori', $dokumen->kategori ?? '') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                                <option value="formulir" {{ old('kategori', $dokumen->kategori ?? '') == 'formulir' ? 'selected' : '' }}>Formulir</option>
                                <option value="lainnya" {{ old('kategori', $dokumen->kategori ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $dokumen->deskripsi ?? 'Dokumen peraturan terbaru untuk Inspektorat Papua Tengah tahun 2024') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="file" class="form-label">File Dokumen</label>
                            @if(isset($dokumen->file_path) && $dokumen->file_path)
                                <div class="mb-2">
                                    <div class="border rounded p-2 bg-light">
                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                        <span>{{ $dokumen->nama_file ?? 'peraturan_inspektorat_2024.pdf' }}</span>
                                        <small class="text-muted">({{ $dokumen->ukuran ?? '2.5 MB' }})</small>
                                    </div>
                                    <p class="text-muted mt-1 mb-0">File saat ini</p>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                   id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format yang diizinkan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX. Maksimal 10MB. Kosongkan jika tidak ingin mengubah file.</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="aktif" {{ old('status', $dokumen->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $dokumen->status ?? '') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                   id="tags" name="tags" value="{{ old('tags', $dokumen->tags ?? 'peraturan, inspektorat, 2024') }}" 
                                   placeholder="Pisahkan dengan koma">
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Contoh: audit, internal, laporan</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="is_public" class="form-label">Akses Publik</label>
                            <select class="form-select @error('is_public') is-invalid @enderror" id="is_public" name="is_public">
                                <option value="1" {{ old('is_public', $dokumen->is_public ?? '1') == '1' ? 'selected' : '' }}>Ya, bisa diakses publik</option>
                                <option value="0" {{ old('is_public', $dokumen->is_public ?? '') == '0' ? 'selected' : '' }}>Tidak, hanya admin</option>
                            </select>
                            @error('is_public')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="gambar_preview" class="form-label">Gambar Preview (Opsional)</label>
                    @if(isset($dokumen->gambar_preview) && $dokumen->gambar_preview)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $dokumen->gambar_preview) }}" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            <p class="text-muted mt-1">Gambar preview saat ini</p>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('gambar_preview') is-invalid @enderror" 
                           id="gambar_preview" name="gambar_preview" accept="image/*">
                    @error('gambar_preview')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB. Untuk preview dokumen di halaman publik. Kosongkan jika tidak ingin mengubah gambar.</div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.dokumen.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        if (fileSize > 10) {
            alert('Ukuran file terlalu besar. Maksimal 10MB.');
            e.target.value = '';
        }
    }
});

document.getElementById('gambar_preview').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        if (fileSize > 2) {
            alert('Ukuran gambar terlalu besar. Maksimal 2MB.');
            e.target.value = '';
        }
    }
});
</script>
@endpush
@endsection
