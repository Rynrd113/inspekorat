@extends('layouts.public')

@section('title', 'Galeri Kegiatan - Inspektorat Papua Tengah')
@section('description', 'Galeri foto dan video kegiatan Inspektorat Provinsi Papua Tengah')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm text-blue-200 mb-6">
                <a href="{{ route('public.index') }}" class="hover:text-white">Beranda</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <span class="text-white">Galeri</span>
            </nav>

            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4">
                    <i class="fas fa-images text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Galeri Kegiatan</h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                    Dokumentasi foto dan video kegiatan Inspektorat Provinsi Papua Tengah
                </p>
            </div>
        </div>
    </div>

    <!-- Albums Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($albums->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($albums as $album)
            <a href="{{ route('public.album', $album->slug) }}" 
               class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <!-- Cover Image -->
                <div class="relative h-56 bg-gray-200 overflow-hidden">
                    <img src="{{ $album->cover_image_url }}" 
                         alt="{{ $album->nama_album }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
                    
                    <!-- Photo Count Badge -->
                    <div class="absolute bottom-3 right-3 bg-white bg-opacity-90 px-3 py-1 rounded-full">
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
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        {{ $album->deskripsi }}
                    </p>
                    @endif

                    <div class="flex items-center justify-between text-sm text-gray-500">
                        @if($album->tanggal_kegiatan)
                        <span>
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $album->tanggal_kegiatan->format('d M Y') }}
                        </span>
                        @endif
                        
                        @if($album->children->count() > 0)
                        <span>
                            <i class="fas fa-folder mr-1"></i>
                            {{ $album->children->count() }} Sub Album
                        </span>
                        @endif
                    </div>

                    <!-- View Button -->
                    <div class="mt-4 pt-4 border-t">
                        <span class="text-blue-600 font-semibold group-hover:text-blue-700">
                            Lihat Album
                            <i class="fas fa-arrow-right ml-2"></i>
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
        <div class="text-center py-20">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                <i class="fas fa-images text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Album</h3>
            <p class="text-gray-600">Album galeri akan segera ditambahkan</p>
        </div>
        @endif
    </div>
</div>
@endsection
