@extends('layouts.admin')

@section('title', 'Tambah Media Galeri')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Media Galeri</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
                <li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
                <li><a href="{{ route('admin.galeri.index') }}" class="text-blue-600 hover:text-blue-800">Galeri</a></li>
                <li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
                <li class="text-gray-600">Tambah</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-plus mr-2 text-blue-600"></i>
                Form Tambah Media Galeri
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('judul') border-red-500 @enderror" 
                               id="judul" 
                               name="judul" 
                               value="{{ old('judul') }}" 
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
                            <option value="foto" {{ old('tipe') == 'foto' ? 'selected' : '' }}>Foto</option>
                            <option value="video" {{ old('tipe') == 'video' ? 'selected' : '' }}>Video</option>
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
                            <option value="kegiatan" {{ old('kategori') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                            <option value="acara" {{ old('kategori') == 'acara' ? 'selected' : '' }}>Acara</option>
                            <option value="fasilitas" {{ old('kategori') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                            <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
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
                              rows="3">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload for Foto -->
                <div id="fotoFields" style="display: none;">
                    <div class="mt-6">
                        <label for="file_foto" class="block text-sm font-medium text-gray-700 mb-2">
                            File Foto <span class="text-red-500">*</span>
                        </label>
                        <input type="file" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('file_foto') border-red-500 @enderror" 
                               id="file_foto" 
                               name="file_foto" 
                               accept="image/*">
                        @error('file_foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG, GIF. Maksimal 5MB.</p>
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
                            <input type="file" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('file_video') border-red-500 @enderror" 
                                   id="file_video" 
                                   name="file_video" 
                                   accept="video/*">
                            @error('file_video')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Format: MP4, AVI, MOV. Maksimal 50MB.</p>
                        </div>
                        <div>
                            <label for="url_video" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Video (YouTube/Vimeo)
                            </label>
                            <input type="url" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('url_video') border-red-500 @enderror" 
                                   id="url_video" 
                                   name="url_video" 
                                   value="{{ old('url_video') }}" 
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
                        <input type="file" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('thumbnail') border-red-500 @enderror" 
                               id="thumbnail" 
                               name="thumbnail" 
                               accept="image/*">
                        @error('thumbnail')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Gambar preview untuk video. Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
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
                               value="{{ old('tags') }}" 
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
                               value="{{ old('tanggal_ambil') }}">
                        @error('tanggal_ambil')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.galeri.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-save mr-2"></i>Simpan
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

// Validasi file video
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
