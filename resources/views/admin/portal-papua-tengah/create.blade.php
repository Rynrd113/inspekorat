@extends('layouts.admin')

@section('header', 'Tambah Berita')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600">Dashboard</a>
    <i class="fas fa-chevron-right mx-2 text-gray-300"></i>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.portal-papua-tengah.index') }}" class="text-gray-400 hover:text-gray-600">Portal Berita</a>
    <i class="fas fa-chevron-right mx-2 text-gray-300"></i>
</li>
<li class="text-gray-600">Tambah Berita</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Berita Baru</h1>
            <p class="text-gray-600">Buat berita atau pengumuman baru</p>
        </div>
        <a href="{{ route('admin.portal-papua-tengah.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.portal-papua-tengah.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Konten Utama -->
            <div class="lg:col-span-2 space-y-6">
                <x-card>
                    <div class="space-y-6">
                        <!-- Judul -->
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700">Judul Berita *</label>
                            <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('judul') border-red-500 @enderror">
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('slug') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">URL-friendly version of the title. Leave empty to auto-generate.</p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Konten -->
                        <div>
                            <label for="konten" class="block text-sm font-medium text-gray-700">Konten *</label>
                            <textarea id="konten" name="konten" rows="10" required 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('konten') border-red-500 @enderror">{{ old('konten') }}</textarea>
                            @error('konten')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                            <textarea id="meta_description" name="meta_description" rows="3" maxlength="160" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('meta_description') border-red-500 @enderror">{{ old('meta_description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Deskripsi singkat untuk SEO (max 160 karakter)</p>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Tags -->
                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                            <input type="text" id="tags" name="tags" value="{{ old('tags') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tags') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Pisahkan dengan koma, contoh: berita, pengumuman, kegiatan</p>
                            @error('tags')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-card>
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Pengaturan Publikasi -->
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Publikasi</h3>
                    <div class="space-y-4">
                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori *</label>
                            <select id="kategori" name="kategori" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('kategori') border-red-500 @enderror">
                                <option value="">Pilih Kategori</option>
                                <option value="berita" {{ old('kategori') === 'berita' ? 'selected' : '' }}>Berita</option>
                                <option value="pengumuman" {{ old('kategori') === 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                                <option value="kegiatan" {{ old('kategori') === 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                <option value="regulasi" {{ old('kategori') === 'regulasi' ? 'selected' : '' }}>Regulasi</option>
                                <option value="layanan" {{ old('kategori') === 'layanan' ? 'selected' : '' }}>Layanan</option>
                            </select>
                            @error('kategori')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Penulis -->
                        <div>
                            <label for="penulis" class="block text-sm font-medium text-gray-700">Penulis *</label>
                            <input type="text" id="penulis" name="penulis" value="{{ old('penulis', 'Humas Inspektorat Papua Tengah') }}" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('penulis') border-red-500 @enderror">
                            @error('penulis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Status Publikasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="mt-2 space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="is_published" value="0" {{ old('is_published', '0') == '0' ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Simpan sebagai Draft</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="is_published" value="1" {{ old('is_published') == '1' ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Publikasikan Sekarang</span>
                                </label>
                            </div>
                            @error('is_published')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Tanggal Publikasi -->
                        <div>
                            <label for="published_at" class="block text-sm font-medium text-gray-700">Tanggal Publikasi</label>
                            <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('published_at') border-red-500 @enderror">
                            @error('published_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Featured -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Berita Unggulan</span>
                            </label>
                        </div>
                    </div>
                </x-card>
                
                <!-- Tombol Aksi -->
                <x-card>
                    <div class="space-y-3">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>Simpan Berita
                        </button>
                        <a href="{{ route('admin.portal-papua-tengah.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                    </div>
                </x-card>
            </div>
        </div>
    </form>
</div>

<script>
// Auto-generate slug from title
document.getElementById('judul').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('slug').value = slug;
});

// Character counter for meta description
document.getElementById('meta_description').addEventListener('input', function() {
    const maxLength = 160;
    const currentLength = this.value.length;
    const counter = document.getElementById('meta-counter');
    
    if (!counter) {
        const counterElement = document.createElement('p');
        counterElement.id = 'meta-counter';
        counterElement.className = 'mt-1 text-sm text-gray-500';
        this.parentNode.appendChild(counterElement);
    }
    
    document.getElementById('meta-counter').textContent = `${currentLength}/${maxLength} karakter`;
    
    if (currentLength > maxLength) {
        document.getElementById('meta-counter').className = 'mt-1 text-sm text-red-600';
    } else {
        document.getElementById('meta-counter').className = 'mt-1 text-sm text-gray-500';
    }
});
</script>
@endsection
