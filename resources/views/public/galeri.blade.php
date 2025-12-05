@extends('layouts.public')

@section('title', 'Galeri Foto - Portal Inspektorat Papua Tengah')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">Galeri Kegiatan</h1>
                <p class="text-xl text-blue-100">Dokumentasi kegiatan Inspektorat Provinsi Papua Tengah</p>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('public.index') }}" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-home"></i> Beranda
                        </a>
                    </li>
                    <li>
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    </li>
                    <li class="text-gray-900 font-medium">Galeri</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Albums Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($albums->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($albums as $album)
            <a href="{{ route('public.album', $album->slug) }}" 
               class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                <!-- Album Cover -->
                <div class="relative h-64 bg-gray-200 overflow-hidden">
                    <img src="{{ $album->cover_image_url }}" alt="{{ $album->nama_album }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    
                    <!-- Photo Count Badge -->
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
                        <span class="text-sm font-semibold text-gray-900">
                            <i class="fas fa-images mr-1"></i>
                            {{ $album->getPhotoCount() }} Foto
                        </span>
                    </div>
                </div>

                <!-- Album Info -->
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                        {{ $album->nama_album }}
                    </h3>
                    
                    @if($album->deskripsi)
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $album->deskripsi }}</p>
                    @endif

                    <div class="flex items-center text-sm text-gray-500">
                        @if($album->tanggal_kegiatan)
                        <span>
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $album->tanggal_kegiatan->format('d F Y') }}
                        </span>
                        @endif
                        
                        @if($album->children->count() > 0)
                        <span class="mx-2">•</span>
                        <span>
                            <i class="fas fa-folder mr-1"></i>
                            {{ $album->children->count() }} Sub Album
                        </span>
                        @endif
                    </div>

                    <!-- View Album Button -->
                    <div class="mt-4 pt-4 border-t">
                        <span class="text-blue-600 font-medium group-hover:text-blue-800">
                            Lihat Album <i class="fas fa-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($albums->hasPages())
        <div class="mt-12">
            {{ $albums->links() }}
        </div>
        @endif
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                <i class="fas fa-images text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Album</h3>
            <p class="text-gray-600">Album galeri kegiatan akan segera ditambahkan.</p>
        </div>
        @endif
    </div>
</div>
@endsection
