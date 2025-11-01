@extends('layouts.public')

@use('Illuminate\Support\Facades\Storage')

@section('title', 'Galeri Kegiatan - Inspektorat Papua Tengah')
@section('description', 'Dokumentasi foto dan video kegiatan Inspektorat Provinsi Papua Tengah.')

@section('content')

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <section class="bg-gradient-to-r from-pink-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">
                    Galeri Kegiatan
                </h1>
                <p class="text-xl text-pink-100 max-w-3xl mx-auto">
                    Dokumentasi foto dan video kegiatan Inspektorat Provinsi Papua Tengah
                </p>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-8 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-4 mb-8">
                <button class="filter-btn active px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors" data-filter="all">
                    Semua
                </button>
                <button class="filter-btn px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-filter="foto">
                    Foto
                </button>
                <button class="filter-btn px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-filter="video">
                    Video
                </button>
                <button class="filter-btn px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-filter="dokumen">
                    Dokumen
                </button>
            </div>
        </div>
    </section>

    <!-- Gallery Grid Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($galeris && $galeris->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="gallery-grid">
                    @foreach($galeris as $galeri)
                        <div class="gallery-item group" 
                             data-type="{{ $galeri->file_type ?? 'jpg' }}" 
                             data-category="{{ strtolower($galeri->kategori ?? 'umum') }}">
                            
                            <a href="{{ in_array($galeri->file_type, ['jpg', 'jpeg', 'png', 'gif']) ? asset('uploads/' . $galeri->file_path) : '#' }}" 
                               target="_blank" 
                               class="block bg-white rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                                <!-- Image/Video Thumbnail -->
                                <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                                    @if(in_array($galeri->file_type, ['jpg', 'jpeg', 'png', 'gif']))
                                        @if($galeri->file_path && Storage::disk('public')->exists($galeri->file_path))
                                            <img src="{{ asset('uploads/' . $galeri->file_path) }}" 
                                                 alt="{{ $galeri->judul }}" 
                                                 class="w-full h-full object-cover" 
                                                 loading="lazy">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                                            </div>
                                        @endif
                                        <div class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded text-xs">
                                            <i class="fas fa-image mr-1"></i>Foto
                                        </div>
                                    @elseif(in_array($galeri->file_type, ['mp4', 'avi', 'mov', 'wmv']))
                                        <div class="w-full h-full flex items-center justify-center bg-gray-800">
                                            <i class="fas fa-play-circle text-white text-5xl"></i>
                                        </div>
                                        <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-xs">
                                            <i class="fas fa-video mr-1"></i>Video
                                        </div>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-file text-gray-400 text-4xl"></i>
                                        </div>
                                        <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-xs">
                                            <i class="fas fa-file mr-1"></i>{{ strtoupper($galeri->file_type) }}
                                        </div>
                                    @endif
                                    
                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                        <i class="fas fa-eye text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $galeri->judul }}</h3>
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $galeri->deskripsi }}</p>
                                    
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <span class="bg-gray-100 px-2 py-1 rounded">{{ $galeri->kategori }}</span>
                                        <span>{{ $galeri->tanggal_publikasi ? \Carbon\Carbon::parse($galeri->tanggal_publikasi)->format('d M Y') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($galeris->hasPages())
                <div class="mt-8">
                    {{ $galeris->links() }}
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-camera text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Galeri</h3>
                        <p class="text-gray-500">
                            Foto dan video kegiatan akan segera tersedia di galeri ini.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

@push('scripts')
<script>
// Simple filter functionality
var filterBtns = document.querySelectorAll('.filter-btn');
var galleryItems = document.querySelectorAll('.gallery-item');

for (var i = 0; i < filterBtns.length; i++) {
    filterBtns[i].addEventListener('click', function() {
        var filter = this.getAttribute('data-filter');
        
        // Update active button
        for (var j = 0; j < filterBtns.length; j++) {
            filterBtns[j].classList.remove('bg-blue-600', 'text-white');
            filterBtns[j].classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
        }
        this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
        this.classList.add('bg-blue-600', 'text-white');
        
        // Filter items
        for (var k = 0; k < galleryItems.length; k++) {
            var item = galleryItems[k];
            var itemType = item.getAttribute('data-type');
            var itemFilterType = itemType;
            
            if (itemType === 'jpg' || itemType === 'jpeg' || itemType === 'png' || itemType === 'gif') {
                itemFilterType = 'foto';
            } else if (itemType === 'mp4' || itemType === 'avi' || itemType === 'mov') {
                itemFilterType = 'video';
            } else if (itemType === 'pdf' || itemType === 'doc' || itemType === 'docx') {
                itemFilterType = 'dokumen';
            }
            
            if (filter === 'all' || filter === itemFilterType) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        }
    });
}
</script>
@endpush
@endsection
