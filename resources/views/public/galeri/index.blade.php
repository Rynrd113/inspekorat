@extends('layouts.public')

@section('title', 'Galeri Ke                <div class="gallery-item group cursor-pointer" 
                     data-                    <div class="text-3xl font-bold text-blue-600 mb-2" id="total-photos">
                        {{ isset($galeris) ? $galeris->whereIn('file_type', ['jpg', 'jpeg', 'png', 'gif'])->count() : 0 }}
                    </div>e="{{ $galeri->file_type ?? 'jpg' }}" 
                     data-category="{{ strtolower($galeri->kategori ?? 'umum') }}"
                     onclick="openLightbox({{ $galeri->id ?? 0 }})">an - Inspektorat Papua Tengah')
@section('description', 'Dokumentasi foto dan video kegiatan Inspektorat Provinsi Papua Tengah.')

@section('content')

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <section class="bg-gradient-to-r from-pink-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
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
            <div class="flex flex-wrap justify-center gap-4">
                <button class="filter-btn active px-6 py-3 bg-pink-600 text-white rounded-lg font-medium hover:bg-pink-700 transition-colors" data-filter="all">
                    Semua
                </button>
                <button class="filter-btn px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-filter="foto">
                    Foto
                </button>
                <button class="filter-btn px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-filter="video">
                    Video
                </button>
                <button class="filter-btn px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-filter="rapat">
                    Rapat
                </button>
                <button class="filter-btn px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-filter="sosialisasi">
                    Sosialisasi
                </button>
                <button class="filter-btn px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-filter="audit">
                    Audit
                </button>
                <button class="filter-btn px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-filter="pelatihan">
                    Pelatihan
                </button>
            </div>
        </div>
    </section>

    <!-- Gallery Grid Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="gallery-grid">
                @forelse($galeris ?? [] as $galeri)
                <div class="gallery-item group cursor-pointer" 
                     data-type="{{ $galeri->file_type ?? 'foto' }}" 
                     data-category="{{ strtolower($galeri->kategori ?? 'umum') }}"
                     onclick="openLightbox({{ $galeri->id ?? 0 }})">
                    
                    <div class="relative overflow-hidden rounded-xl bg-white shadow-lg hover:shadow-xl transition-all duration-300 transform group-hover:scale-105">
                        <!-- Image/Video Thumbnail -->
                                                <div class="aspect-w-16 aspect-h-12 bg-gradient-to-br from-pink-100 to-purple-100">
                            @if(in_array(strtolower($galeri->file_type ?? 'jpg'), ['mp4', 'avi', 'mov', 'wmv']))
                                <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                    <i class="fas fa-play-circle text-white text-5xl opacity-80"></i>
                                </div>
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-500 text-4xl opacity-60"></i>
                                </div>
                            @endif

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="bg-white rounded-full p-3">
                                    @if(in_array(strtolower($galeri->file_type ?? 'jpg'), ['mp4', 'avi', 'mov', 'wmv']))
                                        <i class="fas fa-play text-pink-600 text-xl"></i>
                                    @else
                                        <i class="fas fa-search-plus text-pink-600 text-xl"></i>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 text-sm">
                                {{ $galeri->judul ?? 'Galeri Item' }}
                            </h3>
                            
                            @if($galeri->tanggal_publikasi)
                            <div class="flex items-center text-xs text-gray-500 mb-2">
                                <i class="fas fa-calendar w-3 mr-2"></i>
                                <span>{{ \Carbon\Carbon::parse($galeri->tanggal_publikasi)->format('d M Y') }}</span>
                            </div>
                            @endif

                            @if($galeri->deskripsi)
                            <div class="flex items-center text-xs text-gray-500 mb-2">
                                <i class="fas fa-info-circle w-3 mr-2"></i>
                                <span class="line-clamp-1">{{ Str::limit($galeri->deskripsi, 50) }}</span>
                            </div>
                            @endif

                            <!-- Category Badge -->
                            <span class="inline-block px-2 py-1 bg-pink-100 text-pink-800 text-xs rounded-full font-medium">
                                {{ ucfirst($galeri->kategori ?? 'Umum') }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-images text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-medium text-gray-900 mb-4">Belum Ada Galeri</h3>
                    <p class="text-gray-600 max-w-md mx-auto">
                        Foto dan video kegiatan akan segera tersedia di galeri ini.
                    </p>
                </div>
                @endforelse
            </div>

            <!-- Load More Button -->
            @if(isset($galeris) && $galeris->count() >= 12)
            <div class="text-center mt-12">
                <button id="load-more" class="bg-white border border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    Tampilkan Lebih Banyak
                </button>
            </div>
            @endif
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                                        <div class="text-3xl font-bold text-green-600 mb-2" id="total-docs">
                        {{ isset($galeris) ? $galeris->whereIn('file_type', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])->count() : 0 }}
                    </div>
                    <div class="text-gray-600">Foto</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600 mb-2" id="total-videos">
                        {{ isset($galeris) ? $galeris->whereIn('file_type', ['mp4', 'avi', 'mov', 'wmv'])->count() : 0 }}
                    </div>
                    <div class="text-gray-600">Video</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-indigo-600 mb-2" id="total-events">
                        {{ isset($galeris) ? $galeris->unique('tanggal_publikasi')->count() : 0 }}
                    </div>
                    <div class="text-gray-600">Kegiatan</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 mb-2" id="total-categories">
                        {{ isset($galeris) ? $galeris->unique('kategori')->count() : 0 }}
                    </div>
                    <div class="text-gray-600">Kategori</div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center">
    <div class="relative max-w-4xl max-h-full p-4 w-full">
        <!-- Close Button -->
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-2xl z-10 bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-70 transition-all">
            <i class="fas fa-times"></i>
        </button>

        <!-- Navigation Buttons -->
        <button onclick="previousItem()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-2xl z-10 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center hover:bg-opacity-70 transition-all">
            <i class="fas fa-chevron-left"></i>
        </button>
        
        <button onclick="nextItem()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-2xl z-10 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center hover:bg-opacity-70 transition-all">
            <i class="fas fa-chevron-right"></i>
        </button>

        <!-- Content -->
        <div id="lightbox-content" class="text-center">
            <!-- Content will be dynamically loaded here -->
        </div>

        <!-- Info Panel -->
        <div id="lightbox-info" class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-70 text-white p-6">
            <h3 id="lightbox-title" class="text-xl font-semibold mb-2"></h3>
            <div id="lightbox-meta" class="text-sm text-gray-300"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    // Filter functionality
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;

            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-pink-600', 'text-white');
                b.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            });
            
            this.classList.add('active', 'bg-pink-600', 'text-white');
            this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');

            // Filter items
            galleryItems.forEach(item => {
                const itemType = item.dataset.type;
                const itemCategory = item.dataset.category;
                
                // Map file extensions to filter types
                let itemFilterType = itemType;
                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(itemType)) {
                    itemFilterType = 'foto';
                } else if (['mp4', 'avi', 'mov', 'wmv'].includes(itemType)) {
                    itemFilterType = 'video';
                } else if (['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(itemType)) {
                    itemFilterType = 'dokumen';
                }
                
                if (filter === 'all' || 
                    filter === itemFilterType || 
                    filter === itemCategory) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});

// Lightbox functionality
let currentGalleryIndex = 0;
const galleryData = @json($galeris ?? []);

function openLightbox(itemId) {
    const lightbox = document.getElementById('lightbox');
    const content = document.getElementById('lightbox-content');
    const title = document.getElementById('lightbox-title');
    const meta = document.getElementById('lightbox-meta');
    
    // Find item by ID
    const item = galleryData.find(g => g.id === itemId);
    currentGalleryIndex = galleryData.findIndex(g => g.id === itemId);
    
    if (item) {
        // Display content based on file type
        if (['mp4', 'avi', 'mov', 'wmv'].includes(item.file_type)) {
            content.innerHTML = `
                <div class="bg-gray-900 rounded-lg p-8 flex items-center justify-center" style="min-height: 400px;">
                    <div class="text-center">
                        <i class="fas fa-play-circle text-white text-6xl mb-4"></i>
                        <p class="text-white text-lg">Video Player akan segera tersedia</p>
                    </div>
                </div>
            `;
        } else {
            content.innerHTML = `
                <div class="bg-gray-200 rounded-lg flex items-center justify-center" style="min-height: 400px;">
                    <div class="text-center">
                        <i class="fas fa-image text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-600 text-lg">Foto: ${item.judul}</p>
                    </div>
                </div>
            `;
        }
        
        // Update info
        title.textContent = item.judul || 'Galeri Item';
        meta.innerHTML = `
            ${item.tanggal_publikasi ? `<span class="mr-4"><i class="fas fa-calendar mr-1"></i> ${new Date(item.tanggal_publikasi).toLocaleDateString('id-ID')}</span>` : ''}
            ${item.deskripsi ? `<span class="mr-4"><i class="fas fa-map-marker-alt mr-1"></i> ${item.deskripsi}</span>` : ''}
            <span><i class="fas fa-tag mr-1"></i> ${item.kategori || 'Umum'}</span>
        `;
    }
    
    lightbox.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function previousItem() {
    if (galleryData.length > 0) {
        currentGalleryIndex = (currentGalleryIndex - 1 + galleryData.length) % galleryData.length;
        openLightbox(galleryData[currentGalleryIndex].id);
    }
}

function nextItem() {
    if (galleryData.length > 0) {
        currentGalleryIndex = (currentGalleryIndex + 1) % galleryData.length;
        openLightbox(galleryData[currentGalleryIndex].id);
    }
}

// Close lightbox with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    } else if (e.key === 'ArrowLeft') {
        previousItem();
    } else if (e.key === 'ArrowRight') {
        nextItem();
    }
});

// Load more functionality
const loadMoreBtn = document.getElementById('load-more');
if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function() {
        alert('Fitur load more akan segera tersedia');
    });
}
</script>
@endpush
@endsection
