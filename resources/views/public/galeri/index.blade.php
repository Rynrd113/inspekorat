@extends('layouts.public')

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
                        <div class="gallery-item group cursor-pointer" 
                             data-type="{{ $galeri->file_type ?? 'jpg' }}" 
                             data-category="{{ strtolower($galeri->kategori ?? 'umum') }}"
                             onclick="openLightbox({{ $galeri->id ?? 0 }})">
                            
                            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                                <!-- Image/Video Thumbnail -->
                                <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                                    @if(in_array($galeri->file_type, ['jpg', 'jpeg', 'png', 'gif']))
                                        @if($galeri->file_path && Storage::exists('public/' . $galeri->file_path))
                                            <img src="{{ Storage::url($galeri->file_path) }}" 
                                                 alt="{{ $galeri->judul }}" 
                                                 class="w-full h-full object-cover">
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
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center">
                                        <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
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
                            </div>
                        </div>
                    @endforeach
                </div>
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

    <!-- Statistics Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Statistik Galeri</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 mb-2" id="total-photos">
                        {{ isset($galeris) ? $galeris->whereIn('file_type', ['jpg', 'jpeg', 'png', 'gif'])->count() : 0 }}
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
                    <div class="text-3xl font-bold text-green-600 mb-2" id="total-docs">
                        {{ isset($galeris) ? $galeris->whereIn('file_type', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])->count() : 0 }}
                    </div>
                    <div class="text-gray-600">Dokumen</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-orange-600 mb-2" id="total-events">
                        {{ isset($galeris) ? $galeris->unique('tanggal_publikasi')->count() : 0 }}
                    </div>
                    <div class="text-gray-600">Kegiatan</div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center" onclick="closeLightbox()">
    <div class="relative max-w-4xl max-h-full p-4 w-full" onclick="event.stopPropagation()">        
        <!-- Content -->
        <div id="lightbox-content" class="text-center px-16 py-8">
            <!-- Content will be loaded here -->
        </div>
        
        <!-- Info Panel -->
        <div id="lightbox-info" class="absolute bottom-4 left-4 right-4 bg-black bg-opacity-50 text-white p-4 rounded">
            <!-- Info will be loaded here -->
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;

            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white');
                b.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            });
            this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            this.classList.add('bg-blue-600', 'text-white');

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
const galleryData = @json($galeris ?? []);

function openLightbox(itemId) {
    const lightbox = document.getElementById('lightbox');
    const content = document.getElementById('lightbox-content');
    const info = document.getElementById('lightbox-info');
    
    const item = galleryData.find(g => g.id === itemId);
    
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
                <img src="${item.file_path ? '/storage/' + item.file_path : '/images/placeholder.jpg'}" 
                     alt="${item.judul}" 
                     class="max-w-full max-h-[70vh] object-contain rounded-lg mx-auto">
            `;
        }
        
        // Display info
        info.innerHTML = `
            <h3 class="text-xl font-bold mb-2">${item.judul}</h3>
            <p class="text-gray-300 mb-2">${item.deskripsi || ''}</p>
            <div class="flex flex-wrap gap-4 text-sm">
                <span class="bg-blue-600 px-2 py-1 rounded">${item.kategori}</span>
                ${item.tanggal_publikasi ? `<span class="mr-4"><i class="fas fa-calendar mr-1"></i> ${new Date(item.tanggal_publikasi).toLocaleDateString('id-ID')}</span>` : ''}
                ${item.deskripsi ? `<span class="mr-4"><i class="fas fa-map-marker-alt mr-1"></i> ${item.deskripsi}</span>` : ''}
            </div>
        `;
        
        lightbox.classList.remove('hidden');
    }
}

function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
}

// Close lightbox with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});
</script>
@endpush
@endsection
