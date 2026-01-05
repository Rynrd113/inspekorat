@extends('layouts.admin')

@use('Illuminate\Support\Facades\Storage')

@section('title', 'Detail Album: ' . $album->nama_album)

@section('header', 'Detail Album')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.galeri.index') }}" class="text-blue-600 hover:text-blue-800">Galeri</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.albums.index') }}" class="text-blue-600 hover:text-blue-800">Album</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">{{ $album->nama_album }}</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $album->nama_album }}</h1>
            @if($album->parent)
            <p class="text-sm text-purple-600 mt-1">
                <i class="fas fa-folder mr-1"></i>
                Sub-album dari: <a href="{{ route('admin.albums.show', $album->parent) }}" class="hover:underline">{{ $album->parent->nama_album }}</a>
            </p>
            @endif
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.albums.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <a href="{{ route('admin.albums.edit', $album) }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Album
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Album Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <!-- Cover Image -->
                <div class="aspect-video bg-gray-100">
                    @if($album->cover_image)
                        <img src="{{ asset('storage/' . $album->cover_image) }}" 
                             alt="{{ $album->nama_album }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-blue-100">
                            <i class="fas fa-folder text-6xl text-purple-300"></i>
                        </div>
                    @endif
                </div>

                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Album</h3>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @if($album->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Slug</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $album->slug }}</dd>
                        </div>

                        @if($album->tanggal_kegiatan)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Kegiatan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $album->tanggal_kegiatan->format('d F Y') }}</dd>
                        </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jumlah Foto</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $album->getPhotoCount() }} foto</dd>
                        </div>

                        @if($album->children->count() > 0)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sub-Album</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $album->children->count() }} album</dd>
                        </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Urutan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $album->urutan }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $album->created_at->format('d M Y H:i') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Diperbarui</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $album->updated_at->format('d M Y H:i') }}</dd>
                        </div>
                    </dl>

                    @if($album->deskripsi)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Deskripsi</h4>
                        <p class="text-sm text-gray-700">{{ $album->deskripsi }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Upload Photos Form -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 mt-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-cloud-upload-alt mr-2 text-blue-600"></i>
                        Upload Foto ke Album
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.albums.upload-photos', $album) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="space-y-4">
                            <div>
                                <label for="photos" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Foto <span class="text-red-500">*</span>
                                </label>
                                <input type="file" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('photos') border-red-500 @enderror" 
                                       id="photos" 
                                       name="photos[]"
                                       accept="image/*"
                                       multiple
                                       required>
                                <p class="mt-1 text-xs text-gray-500">Format: JPEG, PNG, JPG, GIF, WEBP. Maks 10MB per file.</p>
                                @error('photos')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('photos.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori
                                </label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                        id="kategori" 
                                        name="kategori">
                                    <option value="kegiatan">Kegiatan</option>
                                    <option value="acara">Acara</option>
                                    <option value="fasilitas">Fasilitas</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>

                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-upload mr-2"></i>Upload Foto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Photos and Sub-Albums -->
        <div class="lg:col-span-2">
            <!-- Sub-Albums -->
            @if($album->children->count() > 0)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-folder-tree mr-2 text-purple-600"></i>
                        Sub-Album ({{ $album->children->count() }})
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($album->children as $child)
                        <a href="{{ route('admin.albums.show', $child) }}" 
                           class="block bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors text-center">
                            <div class="w-16 h-16 mx-auto mb-2 bg-gradient-to-br from-purple-100 to-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-folder text-2xl text-purple-400"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $child->nama_album }}</p>
                            <p class="text-xs text-gray-500">{{ $child->getPhotoCount() }} foto</p>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Photos -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-images mr-2 text-blue-600"></i>
                            Foto dalam Album ({{ $photos->total() }})
                        </h3>
                        <a href="{{ route('admin.galeri.create') }}?album_id={{ $album->id }}" 
                           class="text-sm text-blue-600 hover:text-blue-800">
                            <i class="fas fa-plus mr-1"></i>Tambah Foto
                        </a>
                    </div>
                </div>

                @if($photos->count() > 0)
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($photos as $photo)
                        <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden">
                            @if($photo->file_path)
                                <img src="{{ asset('storage/' . $photo->file_path) }}" 
                                     alt="{{ $photo->judul }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-4xl text-gray-300"></i>
                                </div>
                            @endif

                            <!-- Overlay with actions -->
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.galeri.show', $photo->id) }}" 
                                   class="p-2 bg-white rounded-full text-blue-600 hover:bg-blue-50">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.galeri.edit', $photo->id) }}" 
                                   class="p-2 bg-white rounded-full text-yellow-600 hover:bg-yellow-50">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>

                            <!-- Photo title -->
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-2">
                                <p class="text-xs text-white truncate">{{ $photo->judul }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $photos->links() }}
                    </div>
                </div>
                @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-images text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada foto</h3>
                    <p class="text-gray-500 mb-4">Upload foto pertama ke album ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
