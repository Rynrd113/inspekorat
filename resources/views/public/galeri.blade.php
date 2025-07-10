@extends('layouts.public')

@section('title', 'Galeri - Portal Inspektorat Papua Tengah')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Galeri</h1>
                <p class="lead">Dokumentasi kegiatan dan aktivitas Inspektorat Papua Tengah dalam bentuk foto dan video.</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-camera fa-5x opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('public.index') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Galeri</li>
        </ol>
    </div>
</nav>

<!-- Filter Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <select class="form-select" id="filterTipe">
                    <option value="">Semua Tipe</option>
                    <option value="foto">Foto</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterKategori">
                    <option value="">Semua Kategori</option>
                    <option value="kegiatan">Kegiatan</option>
                    <option value="acara">Acara</option>
                    <option value="fasilitas">Fasilitas</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="sortBy">
                    <option value="terbaru">Terbaru</option>
                    <option value="terlama">Terlama</option>
                    <option value="popular">Paling Populer</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchGaleri" placeholder="Cari...">
                    <button class="btn btn-outline-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-5">
    <div class="container">
        <!-- Gallery Tabs -->
        <ul class="nav nav-pills justify-content-center mb-4" id="galleryTabs">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#semua" type="button">
                    <i class="fas fa-th me-2"></i> Semua
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#foto" type="button">
                    <i class="fas fa-image me-2"></i> Foto
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#video" type="button">
                    <i class="fas fa-video me-2"></i> Video
                </button>
            </li>
        </ul>

        <!-- Gallery Content -->
        <div class="tab-content" id="galleryContent">
            <!-- All Media Tab -->
            <div class="tab-pane fade show active" id="semua">
                <div class="row g-3" id="galleryGrid">
                    <!-- Photo Item 1 -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="gallery-item" data-type="foto" data-category="kegiatan">
                            <div class="position-relative overflow-hidden rounded">
                                <img src="https://via.placeholder.com/400x300?text=Kegiatan+Audit" 
                                     class="img-fluid gallery-image" alt="Kegiatan Audit Internal">
                                <div class="gallery-overlay">
                                    <div class="gallery-actions">
                                        <button class="btn btn-light btn-sm" onclick="viewMedia('foto', 'https://via.placeholder.com/800x600?text=Kegiatan+Audit', 'Kegiatan Audit Internal')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm" onclick="shareMedia('Kegiatan Audit Internal')">
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
                                <h6 class="mb-1">Kegiatan Audit Internal</h6>
                                <small class="text-muted">15 Januari 2024</small>
                            </div>
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

@push('styles')
<style>
.gallery-item {
    margin-bottom: 20px;
}

.gallery-image {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.video-thumbnail {
    width: 100%;
    height: 250px;
    background: linear-gradient(135deg, #333 0%, #666 100%);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.05);
}

.gallery-actions {
    margin-bottom: 10px;
}

.gallery-actions .btn {
    margin: 0 5px;
}

.gallery-info {
    text-align: center;
}

.gallery-info .badge {
    margin: 0 2px;
}

.hero-section {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.nav-pills .nav-link {
    color: #007bff;
    border: 1px solid #007bff;
    margin: 0 5px;
}

.nav-pills .nav-link.active {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
@endpush

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
