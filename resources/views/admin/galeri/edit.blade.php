@extends('layouts.admin')

@section('title', 'Edit Media Galeri')

@section('header', 'Edit Media Galeri')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.galeri.index') }}" class="text-blue-600 hover:text-blue-800">Galeri</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Edit</li>
@endsection

@section('main-content')
<div class="space-y-6">

    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-edit mr-2 text-blue-600"></i>
                Form Edit Media Galeri
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('judul') border-red-500 @enderror" 
                               id="judul" 
                               name="judul" 
                               value="{{ old('judul', $galeri->judul ?? 'Kegiatan Audit Internal') }}" 
                               required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tipe" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Media <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tipe') border-red-500 @enderror" 
                                id="tipe" 
                                name="tipe" 
                                required 
                                onchange="toggleMediaFields()">
                            <option value="">Pilih Tipe</option>
                            <option value="foto" {{ old('tipe', $galeri->tipe ?? 'foto') == 'foto' ? 'selected' : '' }}>Foto</option>
                            <option value="video" {{ old('tipe', $galeri->tipe ?? '') == 'video' ? 'selected' : '' }}>Video</option>
                        </select>
                        @error('tipe')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kategori') border-red-500 @enderror" 
                                id="kategori" 
                                name="kategori" 
                                required>
                            <option value="">Pilih Kategori</option>
                            <option value="kegiatan" {{ old('kategori', $galeri->kategori ?? 'kegiatan') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                            <option value="acara" {{ old('kategori', $galeri->kategori ?? '') == 'acara' ? 'selected' : '' }}>Acara</option>
                            <option value="fasilitas" {{ old('kategori', $galeri->kategori ?? '') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                            <option value="lainnya" {{ old('kategori', $galeri->kategori ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="aktif" {{ old('status', $galeri->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $galeri->status ?? '') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror" 
                              id="deskripsi" 
                              name="deskripsi" 
                              rows="3">{{ old('deskripsi', $galeri->deskripsi ?? 'Dokumentasi kegiatan audit internal tahun 2024') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload for Foto -->
                <div id="fotoFields" style="display: none;">
                    <div class="mt-6">
                        <label for="file_foto" class="block text-sm font-medium text-gray-700 mb-2">
                            File Foto
                        </label>
                        @if(isset($galeri->file_path) && $galeri->tipe == 'foto')
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $galeri->file_path) }}" 
                                     alt="Current Photo" 
                                     class="w-48 h-48 object-cover rounded-lg border border-gray-300">
                                <p class="text-sm text-gray-500 mt-1">Foto saat ini</p>
                            </div>
                        @endif
                        <input type="file" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('file_media') border-red-500 @enderror" 
                               id="file_media" 
                               name="file_media" 
                               accept="image/*">
                        @error('file_media')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG, GIF. Maksimal 5MB. Kosongkan jika tidak ingin mengubah foto.</p>
                        <div id="fotoPreview" class="mt-2"></div>
                    </div>
                </div>

                <!-- File Upload for Video -->
                <div id="videoFields" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="file_video" class="block text-sm font-medium text-gray-700 mb-2">
                                File Video
                            </label>
                            @if(isset($galeri->file_path) && $galeri->tipe == 'video' && !$galeri->url_video)
                                <div class="mb-3">
                                    <div class="border rounded-md p-3 bg-gray-50">
                                        <i class="fas fa-video text-blue-600 mr-2"></i>
                                        <span>{{ basename($galeri->file_path) }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Video saat ini</p>
                                </div>
                            @endif
                            <input type="file" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('file_media') border-red-500 @enderror" 
                                   id="file_media" 
                                   name="file_media" 
                                   accept="video/*">
                            @error('file_media')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Format: MP4, AVI, MOV. Maksimal 50MB. Kosongkan jika tidak ingin mengubah video.</p>
                        </div>
                        <div>
                            <label for="url_video" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Video (YouTube/Vimeo)
                            </label>
                            <input type="url" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('url_video') border-red-500 @enderror" 
                                   id="url_video" 
                                   name="url_video" 
                                   value="{{ old('url_video', $galeri->url_video ?? '') }}" 
                                   placeholder="https://www.youtube.com/watch?v=...">
                            @error('url_video')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Opsional. Jika diisi, akan menggunakan URL ini daripada file upload.</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">
                            Thumbnail Video
                        </label>
                        @if(isset($galeri->thumbnail) && $galeri->thumbnail)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $galeri->thumbnail) }}" 
                                     alt="Current Thumbnail" 
                                     class="w-48 h-48 object-cover rounded-lg border border-gray-300">
                                <p class="text-sm text-gray-500 mt-1">Thumbnail saat ini</p>
                            </div>
                        @endif
                        <input type="file" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('thumbnail') border-red-500 @enderror" 
                               id="thumbnail" 
                               name="thumbnail" 
                               accept="image/*">
                        @error('thumbnail')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Gambar preview untuk video. Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah thumbnail.</p>
                        <div id="thumbnailPreview" class="mt-2"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                            Tags
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tags') border-red-500 @enderror" 
                               id="tags" 
                               name="tags" 
                               value="{{ old('tags', $galeri->tags ?? '') }}" 
                               placeholder="Pisahkan dengan koma">
                        @error('tags')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Contoh: audit, kegiatan, 2024</p>
                    </div>
                    
                    <div>
                        <label for="tanggal_ambil" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pengambilan
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_ambil') border-red-500 @enderror" 
                               id="tanggal_ambil" 
                               name="tanggal_ambil" 
                               value="{{ old('tanggal_ambil', $galeri->tanggal_ambil ?? '') }}">
                        @error('tanggal_ambil')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.galeri.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>Update
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
    const fileFoto = document.getElementById('file_media');
    
    if (tipe === 'foto') {
        fotoFields.style.display = 'block';
        videoFields.style.display = 'none';
    } else if (tipe === 'video') {
        fotoFields.style.display = 'none';
        videoFields.style.display = 'block';
    } else {
        fotoFields.style.display = 'none';
        videoFields.style.display = 'none';
    }
}

// Preview foto
document.getElementById('file_media').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('fotoPreview');
    
    if (file && file.type.startsWith('image/')) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        if (fileSize > 5) {
            alert('Ukuran foto terlalu besar. Maksimal 5MB.');
            e.target.value = '';
            preview.innerHTML = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="w-48 h-48 object-cover rounded-lg border border-gray-300 mt-2">`;
        };
        reader.readAsDataURL(file);
    } else if (file && file.type.startsWith('video/')) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        if (fileSize > 50) {
            alert('Ukuran video terlalu besar. Maksimal 50MB.');
            e.target.value = '';
        }
        preview.innerHTML = '';
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
            preview.innerHTML = `<img src="${e.target.result}" class="w-48 h-48 object-cover rounded-lg border border-gray-300 mt-2">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// Set default values on page load
document.addEventListener('DOMContentLoaded', function() {
    const tipe = document.getElementById('tipe').value;
    if (tipe) {
        toggleMediaFields();
    }
});
</script>
@endpush
@endsection
