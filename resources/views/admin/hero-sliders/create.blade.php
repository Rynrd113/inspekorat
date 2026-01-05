@extends('layouts.admin')

@section('title', 'Tambah Foto Slider')

@section('header', 'Tambah Foto Slider')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.hero-sliders.index') }}" class="text-blue-600 hover:text-blue-800">Foto Slider</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Tambah</li>
@endsection

@section('main-content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-image mr-2 text-blue-600"></i>
                Upload Foto Slider Baru
            </h2>
            <p class="text-sm text-gray-500 mt-1">Foto akan ditampilkan di halaman utama website</p>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.hero-sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Image Upload - Primary Field -->
                <div class="mb-6">
                    <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">
                        Foto Slider <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors" id="dropzone">
                        <div class="space-y-2 text-center">
                            <div id="preview-container" class="hidden mb-4">
                                <img id="preview-image" src="" alt="Preview" class="mx-auto max-h-64 rounded-lg shadow-md">
                            </div>
                            <div id="upload-icon">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            </div>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Pilih foto</span>
                                    <input id="gambar" name="gambar" type="file" class="sr-only" accept="image/*" required>
                                </label>
                                <p class="pl-1">atau drag & drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP maksimal 5MB</p>
                            <p class="text-xs text-gray-500">Ukuran ideal: 1920 x 800 pixel</p>
                        </div>
                    </div>
                    @error('gambar')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Caption (Optional) -->
                <div class="mb-6">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul/Caption <span class="text-gray-400">(opsional)</span>
                    </label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
                           placeholder="Contoh: Kegiatan Inspeksi Lapangan 2025"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('judul') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Caption akan ditampilkan di bagian bawah foto</p>
                    @error('judul')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Subtitle (Optional) -->
                <div class="mb-6">
                    <label for="subjudul" class="block text-sm font-medium text-gray-700 mb-2">
                        Sub Judul <span class="text-gray-400">(opsional)</span>
                    </label>
                    <input type="text" id="subjudul" name="subjudul" value="{{ old('subjudul') }}"
                           placeholder="Deskripsi singkat..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('subjudul') border-red-500 @enderror">
                    @error('subjudul')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Order & Status -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                        <input type="number" id="urutan" name="urutan" value="{{ old('urutan', 0) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Urutan kecil tampil duluan</p>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <div class="flex items-end pb-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Aktifkan slider</span>
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.hero-sliders.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('gambar');
    const preview = document.getElementById('preview-image');
    const previewContainer = document.getElementById('preview-container');
    const uploadIcon = document.getElementById('upload-icon');
    const dropzone = document.getElementById('dropzone');

    input.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadIcon.classList.add('hidden');
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => dropzone.classList.add('border-blue-500', 'bg-blue-50'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => dropzone.classList.remove('border-blue-500', 'bg-blue-50'), false);
    });

    dropzone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        input.files = files;
        input.dispatchEvent(new Event('change'));
    });
});
</script>
@endsection
