@extends('layouts.admin')

@section('title', 'Tambah Media Galeri')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Media Galeri</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.galeri.index') }}">Galeri</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Form Tambah Media Galeri
        </div>
        <div class="card-body">
            <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                   id="judul" name="judul" value="{{ old('judul') }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tipe" class="form-label">Tipe Media <span class="text-danger">*</span></label>
                            <select class="form-select @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required onchange="toggleMediaFields()">
                                <option value="">Pilih Tipe</option>
                                <option value="foto" {{ old('tipe') == 'foto' ? 'selected' : '' }}>Foto</option>
                                <option value="video" {{ old('tipe') == 'video' ? 'selected' : '' }}>Video</option>
                            </select>
                            @error('tipe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="kegiatan" {{ old('kategori') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                <option value="acara" {{ old('kategori') == 'acara' ? 'selected' : '' }}>Acara</option>
                                <option value="fasilitas" {{ old('kategori') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                                <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- File Upload for Foto -->
                <div id="fotoFields" style="display: none;">
                    <div class="mb-3">
                        <label for="file_foto" class="form-label">File Foto <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('file_foto') is-invalid @enderror" 
                               id="file_foto" name="file_foto" accept="image/*">
                        @error('file_foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: JPG, JPEG, PNG, GIF. Maksimal 5MB.</div>
                        <div id="fotoPreview" class="mt-2"></div>
                    </div>
                </div>

                <!-- File Upload for Video -->
                <div id="videoFields" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file_video" class="form-label">File Video</label>
                                <input type="file" class="form-control @error('file_video') is-invalid @enderror" 
                                       id="file_video" name="file_video" accept="video/*">
                                @error('file_video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Format: MP4, AVI, MOV. Maksimal 50MB.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="url_video" class="form-label">URL Video (YouTube/Vimeo)</label>
                                <input type="url" class="form-control @error('url_video') is-invalid @enderror" 
                                       id="url_video" name="url_video" value="{{ old('url_video') }}" 
                                       placeholder="https://www.youtube.com/watch?v=...">
                                @error('url_video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Opsional. Jika diisi, akan menggunakan URL ini daripada file upload.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail Video</label>
                        <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                               id="thumbnail" name="thumbnail" accept="image/*">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Gambar preview untuk video. Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
                        <div id="thumbnailPreview" class="mt-2"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                   id="tags" name="tags" value="{{ old('tags') }}" 
                                   placeholder="Pisahkan dengan koma">
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Contoh: audit, kegiatan, 2024</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_ambil" class="form-label">Tanggal Pengambilan</label>
                            <input type="date" class="form-control @error('tanggal_ambil') is-invalid @enderror" 
                                   id="tanggal_ambil" name="tanggal_ambil" value="{{ old('tanggal_ambil') }}">
                            @error('tanggal_ambil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleMediaFields() {
    const tipe = document.getElementById('tipe').value;
    const fotoFields = document.getElementById('fotoFields');
    const videoFields = document.getElementById('videoFields');
    const fileFoto = document.getElementById('file_foto');
    
    if (tipe === 'foto') {
        fotoFields.style.display = 'block';
        videoFields.style.display = 'none';
        fileFoto.required = true;
    } else if (tipe === 'video') {
        fotoFields.style.display = 'none';
        videoFields.style.display = 'block';
        fileFoto.required = false;
    } else {
        fotoFields.style.display = 'none';
        videoFields.style.display = 'none';
        fileFoto.required = false;
    }
}

// Preview foto
document.getElementById('file_foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('fotoPreview');
    
    if (file) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        if (fileSize > 5) {
            alert('Ukuran foto terlalu besar. Maksimal 5MB.');
            e.target.value = '';
            preview.innerHTML = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// Preview thumbnail
document.getElementById('thumbnail').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('thumbnailPreview');
    
    if (file) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        if (fileSize > 2) {
            alert('Ukuran thumbnail terlalu besar. Maksimal 2MB.');
            e.target.value = '';
            preview.innerHTML = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// Check video file size
document.getElementById('file_video').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        if (fileSize > 50) {
            alert('Ukuran video terlalu besar. Maksimal 50MB.');
            e.target.value = '';
        }
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleMediaFields();
});
</script>
@endpush
@endsection
