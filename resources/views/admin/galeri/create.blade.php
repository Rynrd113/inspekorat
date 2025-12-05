@extends('layouts.admin')

@section('title', 'Tambah Media Galeri')

@section('header', 'Tambah Media Galeri')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.galeri.index') }}" class="text-blue-600 hover:text-blue-800">Galeri</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Tambah</li>
@endsection

@section('main-content')
<div class="space-y-6">

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
                        <label for="album_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Album <span class="text-gray-500 text-xs">(Opsional)</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('album_id') border-red-500 @enderror" 
                                id="album_id" 
                                name="album_id">
                            <option value="">-- Pilih Album --</option>
                            @if(isset($albums))
                            @foreach($albums as $album)
                            <option value="{{ $album->id }}" {{ old('album_id') == $album->id ? 'selected' : '' }}>
                                {{ $album->nama_album }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                        @error('album_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
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
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="tanggal_publikasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Publikasi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_publikasi') border-red-500 @enderror" 
                               id="tanggal_publikasi" 
                               name="tanggal_publikasi" 
                               value="{{ old('tanggal_publikasi') }}" 
                               required>
                        @error('tanggal_publikasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="flex items-center">
                            <input type="checkbox" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                   id="status" 
                                   name="status" 
                                   value="1"
                                   {{ old('status') ? 'checked' : 'checked' }}>
                            <span class="ml-2 text-sm font-medium text-gray-700">Status Aktif</span>
                        </label>
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

                <div class="mt-6">
                    <label for="file_galeri" class="block text-sm font-medium text-gray-700 mb-2">
                        File Media <span class="text-red-500">*</span>
                    </label>
                    <input type="file" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('file_galeri') border-red-500 @enderror" 
                           id="file_galeri" 
                           name="file_galeri" 
                           accept="image/*,video/*"
                           required>
                    @error('file_galeri')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG, GIF untuk gambar atau MP4, AVI, MOV untuk video. Maksimal 20MB.</p>
                    <div id="filePreview" class="mt-2"></div>
                </div>

                <div class="mt-6">
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">
                        Thumbnail (Opsional)
                    </label>
                    <input type="file" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('thumbnail') border-red-500 @enderror" 
                           id="thumbnail" 
                           name="thumbnail" 
                           accept="image/*">
                    @error('thumbnail')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Gambar preview untuk media. Format: JPG, JPEG, PNG, GIF. Maksimal 5MB.<br>
                        <strong>Catatan:</strong> Jika tidak diisi, untuk foto akan otomatis menggunakan foto itu sendiri sebagai thumbnail.
                    </p>
                    <div id="thumbnailPreview" class="mt-2"></div>
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
                        <label for="album" class="block text-sm font-medium text-gray-700 mb-2">
                            Album
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('album') border-red-500 @enderror" 
                               id="album" 
                               name="album" 
                               value="{{ old('album') }}" 
                               placeholder="Nama album">
                        @error('album')
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
// File preview functionality
document.getElementById('file_galeri').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('filePreview');
    
    if (file) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        const maxSize = 20; // 20MB
        
        if (fileSize > maxSize) {
            alert(`Ukuran file terlalu besar. Maksimal ${maxSize}MB.`);
            e.target.value = '';
            preview.innerHTML = '';
            return;
        }
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-48 h-48 object-cover rounded-lg border border-gray-300 mt-2">`;
            };
            reader.readAsDataURL(file);
        } else if (file.type.startsWith('video/')) {
            preview.innerHTML = `<div class="border rounded-md p-3 bg-gray-50 mt-2">
                <i class="fas fa-video text-blue-600 mr-2"></i>
                <span>${file.name}</span>
                <span class="text-gray-500 text-sm ml-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
            </div>`;
        } else {
            preview.innerHTML = '';
        }
    } else {
        preview.innerHTML = '';
    }
});

// Thumbnail preview functionality
document.getElementById('thumbnail').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('thumbnailPreview');
    
    if (file) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        const maxSize = 5; // 5MB
        
        if (fileSize > maxSize) {
            alert(`Ukuran thumbnail terlalu besar. Maksimal ${maxSize}MB.`);
            e.target.value = '';
            preview.innerHTML = '';
            return;
        }
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-32 h-32 object-cover rounded-lg border border-gray-300 mt-2">`;
            };
            reader.readAsDataURL(file);
        } else {
            alert('Thumbnail harus berupa file gambar.');
            e.target.value = '';
            preview.innerHTML = '';
        }
    } else {
        preview.innerHTML = '';
    }
});
</script>
@endpush
@endsection
