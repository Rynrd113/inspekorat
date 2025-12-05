@extends('layouts.public')

@section('title', $album->nama_album . ' - Galeri Inspektorat Papua Tengah')

@section('content')
<div class="min-h-screen bg-gray-50">
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
                    <li>
                        <a href="{{ route('public.galeri.index') }}" class="text-gray-500 hover:text-gray-700">
                            Galeri
                        </a>
                    </li>
                    @if($album->parent)
                    @foreach($album->getBreadcrumbs()->slice(0, -1) as $parentAlbum)
                    <li>
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    </li>
                    <li>
                        <a href="{{ route('public.album', $parentAlbum->slug) }}" class="text-gray-500 hover:text-gray-700">
                            {{ $parentAlbum->nama_album }}
                        </a>
                    </li>
                    @endforeach
                    @endif
                    <li>
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    </li>
                    <li class="text-gray-900 font-medium">{{ $album->nama_album }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Album Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $album->nama_album }}</h1>
                    
                    @if($album->deskripsi)
                    <p class="text-gray-600 text-lg mb-4">{{ $album->deskripsi }}</p>
                    @endif

                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        @if($album->tanggal_kegiatan)
                        <span>
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $album->tanggal_kegiatan->format('d F Y') }}
                        </span>
                        @endif
                        <span>
                            <i class="fas fa-images mr-1"></i>
                            {{ $album->getPhotoCount() }} Foto
                        </span>
                    </div>
                </div>
                
                <a href="{{ $album->parent ? route('public.album', $album->parent->slug) : route('public.galeri.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Sub Albums -->
        @if($album->children->count() > 0)
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Sub Album</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($album->children as $child)
                <a href="{{ route('public.album', $child->slug) }}" 
                   class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-all overflow-hidden">
                    <div class="h-40 bg-gray-200 overflow-hidden">
                        <img src="{{ $child->cover_image_url }}" alt="{{ $child->nama_album }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1 truncate">{{ $child->nama_album }}</h3>
                        <p class="text-sm text-gray-500">{{ $child->getPhotoCount() }} Foto</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Photos Grid -->
        @if($photos->count() > 0)
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Foto</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($photos as $index => $photo)
                <div class="group relative aspect-square bg-gray-200 rounded-lg overflow-hidden cursor-pointer"
                     onclick="openLightbox({{ $index }})">
                    <img src="{{ asset('storage/' . $photo->file_path) }}" 
                         alt="{{ $photo->judul }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    
                    <!-- Overlay on Hover -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </div>

                    <!-- Photo Title -->
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                        <p class="text-white text-sm font-medium truncate">{{ $photo->judul }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($photos->hasPages())
            <div class="mt-8">
                {{ $photos->links() }}
            </div>
            @endif
        </div>
        @else
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                <i class="fas fa-images text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Foto</h3>
            <p class="text-gray-600">Foto untuk album ini akan segera ditambahkan.</p>
        </div>
        @endif
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-95 hidden z-50 flex items-center justify-center">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-10">
        <i class="fas fa-times"></i>
    </button>

    <button onclick="previousPhoto()" class="absolute left-4 text-white text-4xl hover:text-gray-300 z-10">
        <i class="fas fa-chevron-left"></i>
    </button>

    <button onclick="nextPhoto()" class="absolute right-4 text-white text-4xl hover:text-gray-300 z-10">
        <i class="fas fa-chevron-right"></i>
    </button>

    <div class="max-w-5xl max-h-screen p-4">
        <img id="lightbox-image" src="" alt="" class="max-w-full max-h-screen object-contain">
        <div class="text-center mt-4">
            <h3 id="lightbox-title" class="text-white text-xl font-semibold"></h3>
            <p id="lightbox-date" class="text-gray-300 text-sm mt-2"></p>
            <p id="lightbox-counter" class="text-gray-400 text-sm mt-2"></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const photos = @json($photos->items());
let currentPhotoIndex = 0;

function openLightbox(index) {
    currentPhotoIndex = index;
    showPhoto();
    document.getElementById('lightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function showPhoto() {
    const photo = photos[currentPhotoIndex];
    document.getElementById('lightbox-image').src = '/storage/' + photo.file_path;
    document.getElementById('lightbox-title').textContent = photo.judul;
    document.getElementById('lightbox-date').textContent = new Date(photo.tanggal_publikasi).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
    document.getElementById('lightbox-counter').textContent = `${currentPhotoIndex + 1} / ${photos.length}`;
}

function nextPhoto() {
    currentPhotoIndex = (currentPhotoIndex + 1) % photos.length;
    showPhoto();
}

function previousPhoto() {
    currentPhotoIndex = (currentPhotoIndex - 1 + photos.length) % photos.length;
    showPhoto();
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (document.getElementById('lightbox').classList.contains('hidden')) return;
    
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') nextPhoto();
    if (e.key === 'ArrowLeft') previousPhoto();
});
</script>
@endpush
