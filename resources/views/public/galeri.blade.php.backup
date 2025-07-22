@extends('layouts.public')

@section('title', 'Galeri - Portal Inspektorat Papua Tengah')

@section('content')
<!-- Navigation -->
<x-navigation />

<!-- Hero Section -->
<section class="hero-section text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center">
            <div class="lg:w-2/3 text-center lg:text-left">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">Galeri</h1>
                <p class="text-xl text-blue-100">Dokumentasi kegiatan dan aktivitas Inspektorat Papua Tengah dalam bentuk foto dan video.</p>
            </div>
            <div class="lg:w-1/3 text-center mt-8 lg:mt-0">
                <i class="fas fa-camera text-8xl opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-gray-100 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('public.index') }}" class="text-blue-600 hover:text-blue-800">Beranda</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-700">Galeri</li>
        </ol>
    </div>
</nav>

<!-- Filter Section -->
<section class="py-6 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="filterTipe">
                    <option value="">Semua Tipe</option>
                    <option value="foto">Foto</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="filterKategori">
                    <option value="">Semua Kategori</option>
                    <option value="kegiatan">Kegiatan</option>
                    <option value="acara">Acara</option>
                    <option value="fasilitas">Fasilitas</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="sortBy">
                    <option value="terbaru">Terbaru</option>
                    <option value="terlama">Terlama</option>
                    <option value="popular">Paling Populer</option>
                </select>
            </div>
            <div>
                <div class="flex">
                    <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="searchGaleri" placeholder="Cari...">
                    <button class="px-4 py-2 bg-blue-600 text-white border border-blue-600 rounded-r-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Gallery Tabs -->
        <div class="flex justify-center mb-8" id="galleryTabs">
            <div class="inline-flex bg-gray-100 rounded-lg p-1">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-md transition-colors" data-target="#semua" type="button">
                    <i class="fas fa-th mr-2"></i> Semua
                </button>
                <button class="px-4 py-2 text-gray-700 hover:text-blue-600 rounded-md transition-colors" data-target="#foto" type="button">
                    <i class="fas fa-image mr-2"></i> Foto
                </button>
                <button class="px-4 py-2 text-gray-700 hover:text-blue-600 rounded-md transition-colors" data-target="#video" type="button">
                    <i class="fas fa-video mr-2"></i> Video
                </button>
            </div>
        </div>

        <!-- Gallery Content -->
        <div id="galleryContent">
            <!-- All Media Tab -->
            <div class="block" id="semua">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="galleryGrid">
                    <!-- Photo Item 1 -->
                    <div class="gallery-item" data-type="foto" data-category="kegiatan">
                        <div class="relative overflow-hidden rounded-lg">
                            <img src="https://via.placeholder.com/400x300?text=Kegiatan+Audit" 
                                 class="w-full gallery-image" alt="Kegiatan Audit Internal">
                            <div class="gallery-overlay">
                                <div class="gallery-actions">
                                    <button class="px-3 py-2 bg-white text-gray-700 rounded-md text-sm shadow-lg hover:bg-gray-50" onclick="viewMedia('foto', 'https://via.placeholder.com/800x600?text=Kegiatan+Audit', 'Kegiatan Audit Internal')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="px-3 py-2 bg-white text-gray-700 rounded-md text-sm shadow-lg hover:bg-gray-50" onclick="shareMedia('Kegiatan Audit Internal')">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                </div>
                                <div class="gallery-info">
                                    <span class="inline-block px-2 py-1 bg-blue-600 text-white text-xs rounded">Foto</span>
                                    <span class="inline-block px-2 py-1 bg-gray-600 text-white text-xs rounded">Kegiatan</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h6 class="font-semibold text-gray-900 mb-1">Kegiatan Audit Internal</h6>
                            <small class="text-gray-500">15 Januari 2024</small>
                        </div>
                    </div>

                    <!-- Video Item 1 -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="gallery-item" data-type="video" data-category="acara">
                            <div class="position-relative overflow-hidden rounded">
                                <div class="video-thumbnail bg-dark d-flex align-items-center justify-content-center">
                                    <i class="fas fa-play-circle fa-3x text-white"></i>
                                </div>
                                <div class="gallery-overlay">
                                    <div class="gallery-actions">
                                        <button class="btn btn-light btn-sm" onclick="viewMedia('video', '#', 'Sosialisasi WBS')">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm" onclick="shareMedia('Sosialisasi WBS')">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                    <div class="gallery-info">
                                        <span class="badge bg-info">Video</span>
                                        <span class="badge bg-warning">Acara</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h6 class="mb-1">Sosialisasi WBS</h6>
                                <small class="text-muted">10 Januari 2024</small>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Item 2 -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="gallery-item" data-type="foto" data-category="fasilitas">
                            <div class="position-relative overflow-hidden rounded">
                                <img src="https://via.placeholder.com/400x300?text=Fasilitas+Kantor" 
                                     class="img-fluid gallery-image" alt="Fasilitas Kantor">
                                <div class="gallery-overlay">
                                    <div class="gallery-actions">
                                        <button class="btn btn-light btn-sm" onclick="viewMedia('foto', 'https://via.placeholder.com/800x600?text=Fasilitas+Kantor', 'Fasilitas Kantor')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm" onclick="shareMedia('Fasilitas Kantor')">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                    <div class="gallery-info">
                                        <span class="badge bg-primary">Foto</span>
                                        <span class="badge bg-success">Fasilitas</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h6 class="mb-1">Fasilitas Kantor</h6>
                                <small class="text-muted">05 Januari 2024</small>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Item 3 -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="gallery-item" data-type="foto" data-category="kegiatan">
                            <div class="position-relative overflow-hidden rounded">
                                <img src="https://via.placeholder.com/400x300?text=Rapat+Koordinasi" 
                                     class="img-fluid gallery-image" alt="Rapat Koordinasi">
                                <div class="gallery-overlay">
                                    <div class="gallery-actions">
                                        <button class="btn btn-light btn-sm" onclick="viewMedia('foto', 'https://via.placeholder.com/800x600?text=Rapat+Koordinasi', 'Rapat Koordinasi')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm" onclick="shareMedia('Rapat Koordinasi')">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                    <div class="gallery-info">
                                        <span class="badge bg-primary">Foto</span>
                                        <span class="badge bg-secondary">Kegiatan</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h6 class="mb-1">Rapat Koordinasi</h6>
                                <small class="text-muted">01 Januari 2024</small>
                            </div>
                        </div>
                    </div>

                    <!-- Video Item 2 -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="gallery-item" data-type="video" data-category="kegiatan">
                            <div class="position-relative overflow-hidden rounded">
                                <div class="video-thumbnail bg-dark d-flex align-items-center justify-content-center">
                                    <i class="fas fa-play-circle fa-3x text-white"></i>
                                </div>
                                <div class="gallery-overlay">
                                    <div class="gallery-actions">
                                        <button class="btn btn-light btn-sm" onclick="viewMedia('video', '#', 'Workshop Audit')">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm" onclick="shareMedia('Workshop Audit')">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                    <div class="gallery-info">
                                        <span class="badge bg-info">Video</span>
                                        <span class="badge bg-secondary">Kegiatan</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h6 class="mb-1">Workshop Audit</h6>
                                <small class="text-muted">28 Desember 2023</small>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Item 4 -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="gallery-item" data-type="foto" data-category="acara">
                            <div class="position-relative overflow-hidden rounded">
                                <img src="https://via.placeholder.com/400x300?text=Upacara+HUT" 
                                     class="img-fluid gallery-image" alt="Upacara HUT RI">
                                <div class="gallery-overlay">
                                    <div class="gallery-actions">
                                        <button class="btn btn-light btn-sm" onclick="viewMedia('foto', 'https://via.placeholder.com/800x600?text=Upacara+HUT', 'Upacara HUT RI')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm" onclick="shareMedia('Upacara HUT RI')">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                    <div class="gallery-info">
                                        <span class="badge bg-primary">Foto</span>
                                        <span class="badge bg-warning">Acara</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h6 class="mb-1">Upacara HUT RI</h6>
                                <small class="text-muted">17 Agustus 2023</small>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Item 5 -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="gallery-item" data-type="foto" data-category="lainnya">
                            <div class="position-relative overflow-hidden rounded">
                                <img src="https://via.placeholder.com/400x300?text=Team+Building" 
                                     class="img-fluid gallery-image" alt="Team Building">
                                <div class="gallery-overlay">
                                    <div class="gallery-actions">
                                        <button class="btn btn-light btn-sm" onclick="viewMedia('foto', 'https://via.placeholder.com/800x600?text=Team+Building', 'Team Building')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm" onclick="shareMedia('Team Building')">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                    <div class="gallery-info">
                                        <span class="badge bg-primary">Foto</span>
                                        <span class="badge bg-dark">Lainnya</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h6 class="mb-1">Team Building</h6>
                                <small class="text-muted">15 Juli 2023</small>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Item 6 -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="gallery-item" data-type="foto" data-category="fasilitas">
                            <div class="position-relative overflow-hidden rounded">
                                <img src="https://via.placeholder.com/400x300?text=Ruang+Meeting" 
                                     class="img-fluid gallery-image" alt="Ruang Meeting">
                                <div class="gallery-overlay">
                                    <div class="gallery-actions">
                                        <button class="btn btn-light btn-sm" onclick="viewMedia('foto', 'https://via.placeholder.com/800x600?text=Ruang+Meeting', 'Ruang Meeting')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm" onclick="shareMedia('Ruang Meeting')">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                    <div class="gallery-info">
                                        <span class="badge bg-primary">Foto</span>
                                        <span class="badge bg-success">Fasilitas</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h6 class="mb-1">Ruang Meeting</h6>
                                <small class="text-muted">10 Juni 2023</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo Tab -->
            <div class="tab-pane fade" id="foto">
                <div class="row g-3">
                    <!-- Photo items will be populated here -->
                </div>
            </div>

            <!-- Video Tab -->
            <div class="tab-pane fade" id="video">
                <div class="row g-3">
                    <!-- Video items will be populated here -->
                </div>
            </div>
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-4">
            <button class="btn btn-outline-primary btn-lg" id="loadMore">
                <i class="fas fa-plus me-2"></i> Muat Lebih Banyak
            </button>
        </div>
    </div>
</section>

<!-- Media View Modal -->
<div class="modal fade" id="mediaModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaTitle">Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center" id="mediaContent">
                <!-- Media content will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="downloadMedia()">
                    <i class="fas fa-download me-1"></i> Download
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bagikan Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" onclick="shareToFacebook()">
                        <i class="fab fa-facebook-f me-2"></i> Facebook
                    </button>
                    <button class="btn btn-info" onclick="shareToTwitter()">
                        <i class="fab fa-twitter me-2"></i> Twitter
                    </button>
                    <button class="btn btn-success" onclick="shareToWhatsApp()">
                        <i class="fab fa-whatsapp me-2"></i> WhatsApp
                    </button>
                    <button class="btn btn-secondary" onclick="copyLink()">
                        <i class="fas fa-link me-2"></i> Salin Link
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
function viewMedia(type, src, title) {
    document.getElementById('mediaTitle').textContent = title;
    const mediaContent = document.getElementById('mediaContent');
    
    if (type === 'foto') {
        mediaContent.innerHTML = `<img src="${src}" class="img-fluid" alt="${title}">`;
    } else if (type === 'video') {
        mediaContent.innerHTML = `
            <video controls class="w-100" style="max-height: 500px;">
                <source src="${src}" type="video/mp4">
                Browser Anda tidak mendukung tag video.
            </video>
        `;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('mediaModal'));
    modal.show();
}

function shareMedia(title) {
    document.getElementById('shareModal').setAttribute('data-title', title);
    const modal = new bootstrap.Modal(document.getElementById('shareModal'));
    modal.show();
}

function shareToFacebook() {
    const url = window.location.href;
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
}

function shareToTwitter() {
    const title = document.getElementById('shareModal').getAttribute('data-title');
    const url = window.location.href;
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`, '_blank');
}

function shareToWhatsApp() {
    const title = document.getElementById('shareModal').getAttribute('data-title');
    const url = window.location.href;
    window.open(`https://wa.me/?text=${encodeURIComponent(title + ' - ' + url)}`, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link berhasil disalin!');
    });
}

function downloadMedia() {
    // Implement download functionality
    alert('Fungsi download akan diimplementasikan');
}

// Filter functionality
document.getElementById('filterTipe').addEventListener('change', function() {
    filterGallery();
});

document.getElementById('filterKategori').addEventListener('change', function() {
    filterGallery();
});

document.getElementById('searchGaleri').addEventListener('input', function() {
    filterGallery();
});

function filterGallery() {
    const tipe = document.getElementById('filterTipe').value;
    const kategori = document.getElementById('filterKategori').value;
    const search = document.getElementById('searchGaleri').value.toLowerCase();
    
    const items = document.querySelectorAll('.gallery-item');
    
    items.forEach(item => {
        const itemType = item.dataset.type;
        const itemCategory = item.dataset.category;
        const itemTitle = item.querySelector('h6').textContent.toLowerCase();
        
        let show = true;
        
        if (tipe && itemType !== tipe) show = false;
        if (kategori && itemCategory !== kategori) show = false;
        if (search && !itemTitle.includes(search)) show = false;
        
        item.closest('.col-lg-3').style.display = show ? 'block' : 'none';
    });
}

// Tab functionality
document.querySelectorAll('#galleryTabs button').forEach(button => {
    button.addEventListener('click', function() {
        const target = this.getAttribute('data-bs-target');
        if (target === '#foto') {
            document.getElementById('filterTipe').value = 'foto';
        } else if (target === '#video') {
            document.getElementById('filterTipe').value = 'video';
        } else {
            document.getElementById('filterTipe').value = '';
        }
        filterGallery();
    });
});

// Load more functionality
document.getElementById('loadMore').addEventListener('click', function() {
    // Simulate loading more items
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memuat...';
    
    setTimeout(() => {
        this.innerHTML = '<i class="fas fa-plus me-2"></i> Muat Lebih Banyak';
        // In real implementation, you would load more items from server
    }, 2000);
});
</script>
@endpush
