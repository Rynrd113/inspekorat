@extends('layouts.public')

@section('title', 'Galeri Foto - Portal Inspektorat Papua Tengah')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.2.0/css/glightbox.min.css" integrity="sha512-T+KUFi2oYYbNrKDwKe2Vs+qpXM1DlP3t26Xf7QFoJxFKm7peqfnNqKKQwFyoFJ50PBBv19lzIrVa40SSQiUkBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

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
        @if($albums->count() > 0 || ($unassignedGallery && $unassignedGallery->count() > 0))

        <!-- Gallery Items (Legacy/Unassigned) -->
        @if($unassignedGallery && $unassignedGallery->count() > 0)
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                <i class="fas fa-images mr-2 text-blue-600"></i>Galeri Kegiatan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($unassignedGallery as $item)
                @php
                    $imagePath = $item->file_path;
                    $imageUrl = null;
                    $isImage = in_array($item->file_type, ['jpg', 'jpeg', 'png', 'gif', 'webp']);

                    if (file_exists(public_path('storage/' . $imagePath)) || \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                        $imageUrl = asset('storage/' . $imagePath);
                    } elseif (file_exists(public_path('uploads/' . $imagePath))) {
                        $imageUrl = asset('uploads/' . $imagePath);
                    }
                @endphp

                @if($isImage && $imageUrl)
                <a href="{{ $imageUrl }}"
                   class="glightbox group bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden"
                   data-gallery="galeri-kegiatan"
                   data-title="{{ $item->judul }}"
                   data-description="@if($item->deskripsi){{ $item->deskripsi }}@endif">
                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        <img src="{{ $imageUrl }}"
                             alt="{{ $item->judul }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="bg-white/90 rounded-full p-3">
                                <i class="fas fa-search text-2xl text-gray-900"></i>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 line-clamp-2 mb-1 group-hover:text-blue-600 transition-colors">
                            {{ $item->judul }}
                        </h3>
                        <p class="text-sm text-gray-600 line-clamp-1">{{ $item->deskripsi }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $item->kategori }}</span>
                            @if($item->tanggal_publikasi)
                            <span>{{ $item->tanggal_publikasi->format('d M Y') }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @else
                <a href="{{ route('public.galeri.show', $item->id) }}"
                   class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $item->judul }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>

                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 line-clamp-2 mb-1 group-hover:text-blue-600 transition-colors">
                            {{ $item->judul }}
                        </h3>
                        <p class="text-sm text-gray-600 line-clamp-1">{{ $item->deskripsi }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $item->kategori }}</span>
                            @if($item->tanggal_publikasi)
                            <span>{{ $item->tanggal_publikasi->format('d M Y') }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Albums Section -->
        @if($albums->count() > 0)
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                <i class="fas fa-folder mr-2 text-indigo-600"></i>Album Galeri
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($albums as $album)
            <a href="{{ route('public.album', $album->slug) }}"
               class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                <!-- Album Cover -->
                <div class="relative h-64 bg-gray-200 overflow-hidden">
                    <img src="{{ $album->cover_image_url }}" alt="{{ $album->nama_album }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                         loading="lazy">
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

                        @php
                            $activeChildCount = $album->children()
                                ->where('status', true)
                                ->count();
                        @endphp

                        @if($activeChildCount > 0)
                        <span class="mx-2">•</span>
                        <span>
                            <i class="fas fa-folder mr-1"></i>
                            {{ $activeChildCount }} Sub Album
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
                </div>
            </div>

            <!-- Pagination -->
            @if($albums->hasPages())
            <div class="mt-12">
                {{ $albums->links() }}
            </div>
            @endif
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                <i class="fas fa-images text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Galeri</h3>
            <p class="text-gray-600">Galeri kegiatan akan segera ditambahkan.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.2.0/glightbox.min.js" integrity="sha512-ZyKVzuKi8I8eeVsy5W01aL85R5EW/BL7VF3UZFJ9u8ktfVbp0W0n+kiKG3HhpYN0FjL0Hp0wH2gkfbfh3nFvnA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize GLightbox for gallery preview
        const lightbox = GLightbox({
            selector: '.glightbox',
            openEffect: 'fade',
            closeEffect: 'fade',
            width: '90vw',
            height: '90vh',
            draggable: true,
            description: true,
            descPosition: 'bottom'
        });
    });
</script>
@endpush

@endsection
