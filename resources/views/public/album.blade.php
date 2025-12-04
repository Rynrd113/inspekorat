@extends('layouts.public')

@section('title', $album->nama_album . ' - Galeri')
@section('description', $album->deskripsi ?? 'Album galeri ' . $album->nama_album)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm text-blue-200 mb-4">
                <a href="{{ route('public.index') }}" class="hover:text-white">Beranda</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <a href="{{ route('public.galeri.index') }}" class="hover:text-white">Galeri</a>
                
                @foreach($album->getBreadcrumbs() as $breadcrumb)
                <i class="fas fa-chevron-right mx-2"></i>
                <a href="{{ route('public.album', $breadcrumb->slug) }}" 
                   class="hover:text-white {{ $loop->last ? 'text-white font-semibold' : '' }}">
                    {{ $breadcrumb->nama_album }}
                </a>
                @endforeach
            </nav>

            <!-- Album Title -->
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $album->nama_album }}</h1>
                @if($album->deskripsi)
                <p class="text-lg text-blue-100">{{ $album->deskripsi }}</p>
                @endif
                
                <div class="mt-4 flex items-center space-x-4 text-sm text-blue-100">
                    @if($album->tanggal_kegiatan)
                    <span><i class="fas fa-calendar mr-2"></i>{{ $album->tanggal_kegiatan->format('d F Y') }}</span>
                    @endif
                    <span><i class="fas fa-images mr-2"></i>{{ $album->getPhotoCount() }} Foto</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Sub Albums -->
        @if($album->children->count() > 0)
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                <i class="fas fa-folder text-blue-600 mr-2"></i>
                Sub Album
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($album->children as $child)
                <a href="{{ route('public.album', $child->slug) }}" 
                   class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="relative h-40 bg-gray-200 overflow-hidden">
                        <img src="{{ $child->cover_image_url }}" 
                             alt="{{ $child->nama_album }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                        <div class="absolute bottom-2 right-2 bg-white bg-opacity-90 px-2 py-1 rounded-full text-xs font-semibold">
                            {{ $child->getPhotoCount() }} Foto
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                            {{ $child->nama_album }}
                        </h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Photos Grid -->
        @if($photos->count() > 0)
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                <i class="fas fa-images text-blue-600 mr-2"></i>
                Foto
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($photos as $photo)
                <div class="group relative aspect-square bg-gray-200 rounded-lg overflow-hidden cursor-pointer"
                     onclick="openLightbox({{ $loop->index }})">
                    <img src="{{ asset('storage/' . $photo->file_path) }}" 
                         alt="{{ $photo->judul }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    
                    <!-- Overlay on hover -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </div>

                    <!-- Photo Title -->
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                        <p class="text-white text-sm font-medium truncate">{{ $photo->judul }}</p>
                        <p class="text-gray-300 text-xs">{{ $photo->tanggal_publikasi->format('d M Y') }}</p>
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
        @if($album->children->count() == 0)
        <!-- Empty State -->
        <div class="text-center py-20">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                <i class="fas fa-images text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Foto</h3>
            <p class="text-gray-600">Album ini belum memiliki foto</p>
        </div>
        @endif
        @endif
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-95 z-50 hidden flex items-center justify-center">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300">
        <i class="fas fa-times"></i>
    </button>
    
    <button onclick="previousPhoto()" class="absolute left-4 text-white text-4xl hover:text-gray-300">
        <i class="fas fa-chevron-left"></i>
    </button>
    
    <div class="max-w-7xl max-h-screen p-4">
        <img id="lightbox-img" src="" alt="" class="max-w-full max-h-screen object-contain">
        <div class="text-center mt-4">
            <p id="lightbox-title" class="text-white text-xl font-semibold"></p>
            <p id="lightbox-date" class="text-gray-300 text-sm mt-1"></p>
        </div>
    </div>
    
    <button onclick="nextPhoto()" class="absolute right-4 text-white text-4xl hover:text-gray-300">
        <i class="fas fa-chevron-right"></i>
    </button>
</div>
@endsection

@push('scripts')
<script>
const photos = @json($photos->items()->map(function($photo) {
    return [
        'url' => asset('storage/' . $photo->file_path),
        'judul' => $photo->judul,
        'tanggal' => $photo->tanggal_publikasi->format('d M Y')
    ];
}));

let currentIndex = 0;

function openLightbox(index) {
    currentIndex = index;
    updateLightbox();
    document.getElementById('lightbox').classList.remove('hidden');
    document.getElementById('lightbox').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.getElementById('lightbox').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function updateLightbox() {
    if (photos.length > 0) {
        const photo = photos[currentIndex];
        document.getElementById('lightbox-img').src = photo.url;
        document.getElementById('lightbox-title').textContent = photo.judul;
        document.getElementById('lightbox-date').textContent = photo.tanggal;
    }
}

function nextPhoto() {
    currentIndex = (currentIndex + 1) % photos.length;
    updateLightbox();
}

function previousPhoto() {
    currentIndex = (currentIndex - 1 + photos.length) % photos.length;
    updateLightbox();
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const lightbox = document.getElementById('lightbox');
    if (!lightbox.classList.contains('hidden')) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') nextPhoto();
        if (e.key === 'ArrowLeft') previousPhoto();
    }
});
</script>
@endpush
