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
                        <div class="gallery-item group cursor-pointer" 
                             data-type="{{ $galeri->file_type ?? 'jpg' }}" 
                             data-category="{{ strtolower($galeri->kategori ?? 'umum') }}"
                             onclick="openGaleriLightbox({{ $galeri->id ?? 0 }})">
                            
                            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                                <!-- Image/Video Thumbnail -->
                                <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                                    @if(in_array($galeri->file_type, ['jpg', 'jpeg', 'png', 'gif']))
                                        @if($galeri->file_path && Storage::disk('public')->exists($galeri->file_path))
                                            <img src="{{ asset('public/storage/' . $galeri->file_path) }}" 
                                                 alt="{{ $galeri->judul }}" 
                                                 class="w-full h-full object-cover" 
                                                 loading="lazy" 
                                                 decoding="async"
                                                 onerror="this.style.display='none'; const errorDiv = document.createElement('div'); errorDiv.className='w-full h-full flex items-center justify-center bg-gray-100'; const icon = document.createElement('i'); icon.className='fas fa-image text-gray-400 text-4xl'; const text = document.createElement('small'); text.className='text-xs text-gray-500 mt-2 block'; text.textContent='Gambar tidak dapat dimuat'; errorDiv.appendChild(icon); errorDiv.appendChild(text); this.parentElement.appendChild(errorDiv);">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                                                <br><small class="text-xs text-gray-500 mt-2">File tidak ditemukan</small>
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
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden" onclick="closeGaleriLightbox()">
    <!-- Close Button -->
    <button onclick="event.stopPropagation(); closeGaleriLightbox();" class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors p-2" style="z-index: 9999;">
        <i class="fas fa-times text-2xl"></i>
    </button>
    
    <!-- Main Container -->
    <div class="h-full w-full relative" onclick="event.stopPropagation()">        
        <!-- Content Area -->
        <div id="lightbox-content" class="absolute inset-0 flex items-center justify-center p-4" style="bottom: 200px;">
            <!-- Content will be loaded here -->
        </div>
        
        <!-- Info Panel - Fixed at bottom -->
        <div id="lightbox-info" class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-85 text-white p-4 border-t border-gray-700 h-48 overflow-y-auto backdrop-blur-sm">
            <!-- Info will be loaded here -->
        </div>
    </div>
</div>

@push('styles')
<style>
/* Lightbox responsive styles */
#lightbox {
    backdrop-filter: blur(5px);
}

#lightbox-content {
    z-index: 10;
}

#lightbox-info {
    z-index: 20;
}

#lightbox-content img {
    transition: transform 0.3s ease;
    max-height: calc(100vh - 220px) !important;
}

#lightbox-content img:hover {
    transform: scale(1.02);
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    #lightbox-content {
        padding: 1rem;
        bottom: 180px !important;
    }
    
    #lightbox-info {
        height: 180px !important;
        padding: 1rem;
    }
    
    #lightbox-info h3 {
        font-size: 1.125rem;
    }
    
    #lightbox-info p {
        font-size: 0.875rem;
    }
    
    .rounded-full {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
    }
    
    #lightbox-content img {
        max-height: calc(100vh - 200px) !important;
    }
}

@media (max-width: 640px) {
    #lightbox-content {
        bottom: 160px !important;
    }
    
    #lightbox-info {
        height: 160px !important;
    }
    
    #lightbox-content img {
        max-height: calc(100vh - 180px) !important;
    }
}
</style>
@endpush

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
const galleryData = {!! json_encode($galeriData ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) !!};

console.log('Gallery Data loaded:', galleryData ? galleryData.length + ' items' : 'empty');

function openGaleriLightbox(itemId) {
    console.log('Opening lightbox for ID:', itemId);
    const lightbox = document.getElementById('lightbox');
    const content = document.getElementById('lightbox-content');
    const info = document.getElementById('lightbox-info');
    
    const item = galleryData.find(g => g.id === itemId);
    console.log('Found item:', item);
    
    if (item) {
        // Display content based on file type
        if (['mp4', 'avi', 'mov', 'wmv'].includes(item.file_type)) {
            // Clear content first
            content.innerHTML = '';
            
            // Create video placeholder elements safely
            const videoContainer = document.createElement('div');
            videoContainer.className = 'bg-gray-900 rounded-lg p-8 flex items-center justify-center h-96';
            
            const textCenter = document.createElement('div');
            textCenter.className = 'text-center';
            
            const playIcon = document.createElement('i');
            playIcon.className = 'fas fa-play-circle text-white text-6xl mb-4';
            
            const videoText = document.createElement('p');
            videoText.className = 'text-white text-lg';
            videoText.textContent = 'Video Player akan segera tersedia';
            
            textCenter.appendChild(playIcon);
            textCenter.appendChild(videoText);
            videoContainer.appendChild(textCenter);
            content.appendChild(videoContainer);
        } else {
            const storageUrl = '{{ asset("public/storage") }}';
            const placeholderUrl = '{{ asset("images/placeholder.jpg") }}';
            const imageSrc = item.file_path ? `${storageUrl}/${item.file_path}` : placeholderUrl;
            
            // Clear content first
            content.innerHTML = '';
            
            // Create image element safely
            const img = document.createElement('img');
            img.src = imageSrc;
            img.alt = item.judul || '';
            img.className = 'max-w-full object-contain rounded-lg cursor-zoom-in';
            img.style.cssText = 'max-height: calc(100vh - 220px); width: auto; height: auto;';
            img.loading = 'lazy';
            img.onclick = function() { toggleImageZoom(this); };
            img.onerror = function() { this.src = placeholderUrl; };
            
            content.appendChild(img);
        }
        
        // Display info safely with DOM manipulation
        info.innerHTML = '';
        
        const infoContainer = document.createElement('div');
        infoContainer.className = 'flex flex-col md:flex-row md:items-start gap-4';
        
        // Left side content
        const leftDiv = document.createElement('div');
        leftDiv.className = 'flex-1';
        
        const title = document.createElement('h3');
        title.className = 'text-xl font-bold mb-2 text-white';
        title.textContent = item.judul || '';
        leftDiv.appendChild(title);
        
        if (item.deskripsi) {
            const description = document.createElement('p');
            description.className = 'text-gray-300 mb-3 text-sm leading-relaxed';
            description.textContent = item.deskripsi;
            leftDiv.appendChild(description);
        }
        
        // Right side badges
        const rightDiv = document.createElement('div');
        rightDiv.className = 'flex flex-wrap gap-2 text-sm';
        
        // Category badge
        const categoryBadge = document.createElement('span');
        categoryBadge.className = 'bg-blue-600 px-3 py-1 rounded-full text-white';
        categoryBadge.textContent = item.kategori || 'Umum';
        rightDiv.appendChild(categoryBadge);
        
        // Date badge
        if (item.tanggal_publikasi) {
            const dateBadge = document.createElement('span');
            dateBadge.className = 'bg-gray-700 px-3 py-1 rounded-full text-gray-300';
            
            const calendarIcon = document.createElement('i');
            calendarIcon.className = 'fas fa-calendar mr-1';
            dateBadge.appendChild(calendarIcon);
            
            const dateText = document.createTextNode(' ' + new Date(item.tanggal_publikasi).toLocaleDateString('id-ID'));
            dateBadge.appendChild(dateText);
            
            rightDiv.appendChild(dateBadge);
        }
        
        // File type badge
        if (item.file_type) {
            const typeBadge = document.createElement('span');
            typeBadge.className = 'bg-green-600 px-3 py-1 rounded-full text-white';
            typeBadge.textContent = item.file_type.toUpperCase();
            rightDiv.appendChild(typeBadge);
        }
        
        infoContainer.appendChild(leftDiv);
        infoContainer.appendChild(rightDiv);
        info.appendChild(infoContainer);
        
        lightbox.classList.remove('hidden');
    }
}

function closeGaleriLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.add('hidden');
    // Reset scroll position
    document.body.style.overflow = 'auto';
}

// Close lightbox with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGaleriLightbox();
    }
});

// Image zoom functionality
function toggleImageZoom(img) {
    if (img.style.transform === 'scale(2)') {
        img.style.transform = 'scale(1)';
        img.style.cursor = 'zoom-in';
        img.parentElement.style.overflow = 'hidden';
    } else {
        img.style.transform = 'scale(2)';
        img.style.cursor = 'zoom-out';
        img.parentElement.style.overflow = 'auto';
    }
}

// Touch gestures for mobile
let touchStartX = 0;
let touchStartY = 0;

document.addEventListener('touchstart', function(e) {
    touchStartX = e.touches[0].clientX;
    touchStartY = e.touches[0].clientY;
});

document.addEventListener('touchend', function(e) {
    const touchEndX = e.changedTouches[0].clientX;
    const touchEndY = e.changedTouches[0].clientY;
    const deltaX = touchEndX - touchStartX;
    const deltaY = touchEndY - touchStartY;
    
    // Swipe down to close lightbox
    if (Math.abs(deltaY) > Math.abs(deltaX) && deltaY > 100) {
        if (document.getElementById('lightbox').classList.contains('hidden') === false) {
            closeGaleriLightbox();
        }
    }
});
</script>
@endpush
@endsection
