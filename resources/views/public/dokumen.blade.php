@extends('layouts.public')

@section('title', 'Dokumen - Portal Inspektorat Papua Tengah')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Dokumen Publik</h1>
                <p class="lead">Akses dokumen-dokumen penting Inspektorat Papua Tengah yang tersedia untuk publik.</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-file-alt fa-5x opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('public.beranda') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Dokumen</li>
        </ol>
    </div>
</nav>

<!-- Filter Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchDokumen" placeholder="Cari dokumen...">
                    <button class="btn btn-outline-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="filterKategori">
                    <option value="">Semua Kategori</option>
                    <option value="peraturan">Peraturan</option>
                    <option value="panduan">Panduan</option>
                    <option value="laporan">Laporan</option>
                    <option value="formulir">Formulir</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="sortBy">
                    <option value="terbaru">Terbaru</option>
                    <option value="terlama">Terlama</option>
                    <option value="nama_az">Nama A-Z</option>
                    <option value="nama_za">Nama Z-A</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Documents Section -->
<section class="py-5">
    <div class="container">
        <!-- Featured Documents -->
        <div class="mb-5">
            <h3 class="mb-4">Dokumen Terbaru</h3>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm featured-doc">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <div class="doc-preview bg-danger text-white d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-file-pdf fa-3x"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Peraturan Inspektorat Papua Tengah 2024</h5>
                                    <p class="card-text">Dokumen peraturan terbaru untuk Inspektorat Papua Tengah tahun 2024.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary">Peraturan</span>
                                        <small class="text-muted">2.5 MB</small>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">Dipublikasi: 15 Januari 2024</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-outline-primary btn-sm" onclick="previewDocument('#')">
                                    <i class="fas fa-eye me-1"></i> Preview
                                </button>
                                <a href="#" class="btn btn-primary btn-sm" download>
                                    <i class="fas fa-download me-1"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm featured-doc">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <div class="doc-preview bg-info text-white d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-file-pdf fa-3x"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Panduan Audit Internal</h5>
                                    <p class="card-text">Panduan lengkap pelaksanaan audit internal untuk OPD di Papua Tengah.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-info">Panduan</span>
                                        <small class="text-muted">1.8 MB</small>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">Dipublikasi: 10 Januari 2024</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-outline-info btn-sm" onclick="previewDocument('#')">
                                    <i class="fas fa-eye me-1"></i> Preview
                                </button>
                                <a href="#" class="btn btn-info btn-sm" download>
                                    <i class="fas fa-download me-1"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Documents -->
        <h3 class="mb-4">Semua Dokumen</h3>
        <div class="row" id="documentsGrid">
            <!-- Document Card 1 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm doc-card" data-category="formulir">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="doc-icon bg-secondary text-white rounded me-3">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Formulir Pengaduan Masyarakat</h6>
                                <span class="badge bg-secondary">Formulir</span>
                            </div>
                        </div>
                        <p class="card-text small">Formulir standar untuk pengaduan masyarakat melalui sistem WBS.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">156 KB</small>
                            <small class="text-muted">05 Jan 2024</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-outline-secondary btn-sm" onclick="previewDocument('#')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="#" class="btn btn-secondary btn-sm" download>
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Card 2 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm doc-card" data-category="laporan">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="doc-icon bg-success text-white rounded me-3">
                                <i class="fas fa-file-excel"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Laporan Kinerja 2023</h6>
                                <span class="badge bg-success">Laporan</span>
                            </div>
                        </div>
                        <p class="card-text small">Laporan tahunan kinerja Inspektorat Papua Tengah tahun 2023.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">3.2 MB</small>
                            <small class="text-muted">01 Jan 2024</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-outline-success btn-sm" onclick="previewDocument('#')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="#" class="btn btn-success btn-sm" download>
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Card 3 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm doc-card" data-category="panduan">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="doc-icon bg-warning text-white rounded me-3">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Panduan WBS</h6>
                                <span class="badge bg-warning">Panduan</span>
                            </div>
                        </div>
                        <p class="card-text small">Panduan penggunaan Whistle Blowing System untuk masyarakat.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">892 KB</small>
                            <small class="text-muted">28 Des 2023</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-outline-warning btn-sm" onclick="previewDocument('#')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="#" class="btn btn-warning btn-sm" download>
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Card 4 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm doc-card" data-category="lainnya">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="doc-icon bg-primary text-white rounded me-3">
                                <i class="fas fa-file-powerpoint"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Materi Sosialisasi</h6>
                                <span class="badge bg-primary">Lainnya</span>
                            </div>
                        </div>
                        <p class="card-text small">Materi sosialisasi program kerja Inspektorat Papua Tengah.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">5.1 MB</small>
                            <small class="text-muted">25 Des 2023</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-outline-primary btn-sm" onclick="previewDocument('#')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="#" class="btn btn-primary btn-sm" download>
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Card 5 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm doc-card" data-category="peraturan">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="doc-icon bg-danger text-white rounded me-3">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">SOP Pengawasan</h6>
                                <span class="badge bg-danger">Peraturan</span>
                            </div>
                        </div>
                        <p class="card-text small">Standar Operasional Prosedur kegiatan pengawasan internal.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">1.2 MB</small>
                            <small class="text-muted">20 Des 2023</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-outline-danger btn-sm" onclick="previewDocument('#')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="#" class="btn btn-danger btn-sm" download>
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Card 6 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm doc-card" data-category="panduan">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="doc-icon bg-info text-white rounded me-3">
                                <i class="fas fa-file-word"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Panduan Evaluasi</h6>
                                <span class="badge bg-info">Panduan</span>
                            </div>
                        </div>
                        <p class="card-text small">Panduan pelaksanaan evaluasi kinerja organisasi.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">678 KB</small>
                            <small class="text-muted">15 Des 2023</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-outline-info btn-sm" onclick="previewDocument('#')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="#" class="btn btn-info btn-sm" download>
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</section>

<!-- Document Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat preview dokumen...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" class="btn btn-primary" id="downloadFromPreview">
                    <i class="fas fa-download me-1"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.doc-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.doc-preview {
    min-height: 150px;
}

.doc-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.doc-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.featured-doc {
    transition: transform 0.3s ease;
}

.featured-doc:hover {
    transform: translateY(-3px);
}

.hero-section {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}
</style>
@endpush

@push('scripts')
<script>
function previewDocument(url) {
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
    
    // Simulate loading
    setTimeout(() => {
        document.querySelector('#previewModal .modal-body').innerHTML = `
            <div class="text-center">
                <p>Preview dokumen tidak tersedia. Silakan download untuk melihat isi dokumen.</p>
                <i class="fas fa-file-pdf fa-5x text-muted"></i>
            </div>
        `;
    }, 2000);
}

// Search functionality
document.getElementById('searchDokumen').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const docCards = document.querySelectorAll('.doc-card');
    
    docCards.forEach(card => {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        const text = card.querySelector('.card-text').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || text.includes(searchTerm)) {
            card.closest('.col-lg-4').style.display = 'block';
        } else {
            card.closest('.col-lg-4').style.display = 'none';
        }
    });
});

// Filter functionality
document.getElementById('filterKategori').addEventListener('change', function() {
    const selectedCategory = this.value;
    const docCards = document.querySelectorAll('.doc-card');
    
    docCards.forEach(card => {
        const category = card.dataset.category;
        
        if (selectedCategory === '' || category === selectedCategory) {
            card.closest('.col-lg-4').style.display = 'block';
        } else {
            card.closest('.col-lg-4').style.display = 'none';
        }
    });
});

// Sort functionality
document.getElementById('sortBy').addEventListener('change', function() {
    const sortBy = this.value;
    const container = document.getElementById('documentsGrid');
    const cards = Array.from(container.children);
    
    cards.sort((a, b) => {
        const titleA = a.querySelector('.card-title').textContent;
        const titleB = b.querySelector('.card-title').textContent;
        
        switch(sortBy) {
            case 'nama_az':
                return titleA.localeCompare(titleB);
            case 'nama_za':
                return titleB.localeCompare(titleA);
            case 'terbaru':
            case 'terlama':
                // In real implementation, you would sort by actual dates
                return 0;
            default:
                return 0;
        }
    });
    
    // Reorder DOM elements
    cards.forEach(card => container.appendChild(card));
});
</script>
@endpush
