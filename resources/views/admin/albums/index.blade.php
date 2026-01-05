@extends('layouts.admin')

@use('Illuminate\Support\Facades\Storage')

@section('title', 'Manajemen Album')

@section('header', 'Manajemen Album')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.galeri.index') }}" class="text-blue-600 hover:text-blue-800">Galeri</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Album</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Album</h1>
            <p class="text-gray-600 mt-1">Kelola album galeri foto dan video</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.galeri.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-images mr-2"></i>Kembali ke Galeri
            </a>
            <a href="{{ route('admin.albums.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Album
            </a>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <x-search-input 
                            name="search"
                            placeholder="Cari album..."
                            value="{{ request('search') }}"
                            with-icon="true"
                            size="md"
                        />
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <!-- Parent Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parent Album</label>
                        <select name="parent_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Album</option>
                            <option value="root" {{ request('parent_id') == 'root' ? 'selected' : '' }}>Album Utama (Root)</option>
                            @foreach($parentAlbums as $parent)
                            <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->nama_album }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    <x-button type="submit" variant="primary" size="md">
                        <i class="fas fa-search mr-2"></i>Cari
                    </x-button>
                    
                    <x-button 
                        type="button" 
                        variant="secondary" 
                        size="md"
                        onclick="window.location.href='{{ route('admin.albums.index') }}'"
                    >
                        <i class="fas fa-redo mr-2"></i>Reset
                    </x-button>
                </div>
            </form>
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

    <!-- Albums Grid -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-folder-open mr-2 text-purple-600"></i>
                    Daftar Album ({{ $albums->total() }})
                </h2>
            </div>
        </div>

        @if($albums->count() > 0)
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($albums as $album)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                    <!-- Album Cover -->
                    <div class="relative aspect-video bg-gray-100">
                        @if($album->cover_image)
                            <img src="{{ asset('storage/' . $album->cover_image) }}" 
                                 alt="{{ $album->nama_album }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-blue-100">
                                <i class="fas fa-folder text-6xl text-purple-300"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-2 right-2">
                            @if($album->status)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                </span>
                            @endif
                        </div>

                        <!-- Photo Count Badge -->
                        <div class="absolute bottom-2 left-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-black bg-opacity-60 text-white">
                                <i class="fas fa-images mr-1"></i>{{ $album->getPhotoCount() }} foto
                            </span>
                        </div>
                    </div>

                    <!-- Album Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 truncate mb-1" title="{{ $album->nama_album }}">
                            {{ $album->nama_album }}
                        </h3>
                        
                        @if($album->parent)
                        <p class="text-xs text-purple-600 mb-2">
                            <i class="fas fa-folder mr-1"></i>
                            Sub-album dari: {{ $album->parent->nama_album }}
                        </p>
                        @endif

                        @if($album->deskripsi)
                        <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ Str::limit($album->deskripsi, 80) }}</p>
                        @endif

                        @if($album->tanggal_kegiatan)
                        <p class="text-xs text-gray-500 mb-3">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $album->tanggal_kegiatan->format('d M Y') }}
                        </p>
                        @endif

                        <!-- Sub Albums Count -->
                        @if($album->children->count() > 0)
                        <p class="text-xs text-blue-600 mb-3">
                            <i class="fas fa-folder-tree mr-1"></i>
                            {{ $album->children->count() }} sub-album
                        </p>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <a href="{{ route('admin.albums.show', $album) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-eye mr-1"></i>Lihat
                            </a>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.albums.edit', $album) }}" 
                                   class="text-yellow-600 hover:text-yellow-800 text-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.albums.destroy', $album) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus album ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $albums->links() }}
            </div>
        </div>
        @else
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-folder-open text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada album</h3>
            <p class="text-gray-500 mb-4">Mulai dengan membuat album pertama Anda</p>
            <a href="{{ route('admin.albums.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Album
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
