@extends('layouts.admin')

@use('Illuminate\Support\Facades\Storage')

@section('title', 'Edit Album')

@section('header', 'Edit Album')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.galeri.index') }}" class="text-blue-600 hover:text-blue-800">Galeri</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.albums.index') }}" class="text-blue-600 hover:text-blue-800">Album</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Edit: {{ $album->nama_album }}</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-edit mr-2 text-yellow-600"></i>
                Form Edit Album
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.albums.update', $album) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Album -->
                    <div>
                        <label for="nama_album" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Album <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_album') border-red-500 @enderror" 
                               id="nama_album" 
                               name="nama_album" 
                               value="{{ old('nama_album', $album->nama_album) }}" 
                               required>
                        @error('nama_album')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            Slug <span class="text-gray-500 text-xs">(Opsional - Auto generate)</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror" 
                               id="slug" 
                               name="slug" 
                               value="{{ old('slug', $album->slug) }}">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Album -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Parent Album <span class="text-gray-500 text-xs">(Opsional - untuk sub-album)</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('parent_id') border-red-500 @enderror" 
                                id="parent_id" 
                                name="parent_id">
                            <option value="">-- Album Utama (Root) --</option>
                            @foreach($parentAlbums as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $album->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->nama_album }}
                            </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Kegiatan -->
                    <div>
                        <label for="tanggal_kegiatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Kegiatan <span class="text-gray-500 text-xs">(Opsional)</span>
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_kegiatan') border-red-500 @enderror" 
                               id="tanggal_kegiatan" 
                               name="tanggal_kegiatan" 
                               value="{{ old('tanggal_kegiatan', $album->tanggal_kegiatan?->format('Y-m-d')) }}">
                        @error('tanggal_kegiatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Urutan -->
                    <div>
                        <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2">
                            Urutan <span class="text-gray-500 text-xs">(Opsional)</span>
                        </label>
                        <input type="number" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('urutan') border-red-500 @enderror" 
                               id="urutan" 
                               name="urutan" 
                               value="{{ old('urutan', $album->urutan) }}"
                               min="0">
                        @error('urutan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                               id="status" 
                               name="status"
                               {{ old('status', $album->status) ? 'checked' : '' }}>
                        <label for="status" class="ml-2 block text-sm text-gray-900">
                            Status Aktif
                        </label>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="mt-6">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-gray-500 text-xs">(Opsional)</span>
                    </label>
                    <textarea 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror" 
                        id="deskripsi" 
                        name="deskripsi" 
                        rows="4"
                        placeholder="Deskripsi album...">{{ old('deskripsi', $album->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Cover Image -->
                @if($album->cover_image)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cover Saat Ini</label>
                    <div class="relative inline-block">
                        <img src="{{ asset('storage/' . $album->cover_image) }}" 
                             alt="Current cover" 
                             class="max-w-xs rounded-lg shadow-md">
                        <label class="flex items-center mt-2">
                            <input type="checkbox" 
                                   name="remove_cover" 
                                   value="1"
                                   class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="ml-2 text-sm text-red-600">Hapus cover image</span>
                        </label>
                    </div>
                </div>
                @endif

                <!-- Cover Image Upload -->
                <div class="mt-6">
                    <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $album->cover_image ? 'Ganti Cover Album' : 'Cover Album' }} <span class="text-gray-500 text-xs">(Opsional - Max 5MB)</span>
                    </label>
                    <input type="file" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cover_image') border-red-500 @enderror" 
                           id="cover_image" 
                           name="cover_image"
                           accept="image/*">
                    <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPEG, PNG, JPG, GIF, WEBP</p>
                    @error('cover_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-4 hidden">
                        <p class="text-sm text-gray-600 mb-2">Preview Cover Baru:</p>
                        <img id="preview-img" src="" alt="Preview" class="max-w-xs rounded-lg shadow-md">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.albums.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Album
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('cover_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});
</script>
@endpush
@endsection
