@extends('layouts.public')

@section('title', 'Pelayanan - Portal Inspektorat Papua Tengah')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Pelayanan Kami</h1>
                <p class="lead">Berbagai layanan yang tersedia di Inspektorat Papua Tengah untuk mendukung tata kelola pemerintahan yang baik.</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-hands-helping fa-5x opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('public.beranda') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Pelayanan</li>
        </ol>
    </div>
</nav>

<!-- Filter Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchPelayanan" placeholder="Cari layanan...">
                    <button class="btn btn-outline-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="filterKategori">
                    <option value="">Semua Kategori</option>
                    <option value="audit">Audit</option>
                    <option value="konsultasi">Konsultasi</option>
                    <option value="pengaduan">Pengaduan</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Service Card 1 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm service-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon bg-primary text-white rounded-circle me-3">
                                <i class="fas fa-search"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Layanan Audit Internal</h5>
                                <span class="badge bg-primary">Audit</span>
                            </div>
                        </div>
                        <p class="card-text">Layanan audit internal untuk memastikan tata kelola yang baik dan akuntabilitas dalam organisasi pemerintahan.</p>
                        
                        <div class="service-details">
                            <div class="row text-sm mb-3">
                                <div class="col-6">
                                    <strong>Waktu Layanan:</strong><br>
                                    <small class="text-muted">7 hari kerja</small>
                                </div>
                                <div class="col-6">
                                    <strong>Biaya:</strong><br>
                                    <small class="text-success">Gratis</small>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#serviceModal1">
                            <i class="fas fa-info-circle me-1"></i> Detail Layanan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Service Card 2 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm service-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon bg-info text-white rounded-circle me-3">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Konsultasi Tata Kelola</h5>
                                <span class="badge bg-info">Konsultasi</span>
                            </div>
                        </div>
                        <p class="card-text">Layanan konsultasi untuk membantu OPD dalam penerapan tata kelola pemerintahan yang baik.</p>
                        
                        <div class="service-details">
                            <div class="row text-sm mb-3">
                                <div class="col-6">
                                    <strong>Waktu Layanan:</strong><br>
                                    <small class="text-muted">3 hari kerja</small>
                                </div>
                                <div class="col-6">
                                    <strong>Biaya:</strong><br>
                                    <small class="text-success">Gratis</small>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#serviceModal2">
                            <i class="fas fa-info-circle me-1"></i> Detail Layanan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Service Card 3 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm service-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon bg-warning text-white rounded-circle me-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Penanganan Pengaduan</h5>
                                <span class="badge bg-warning">Pengaduan</span>
                            </div>
                        </div>
                        <p class="card-text">Layanan penanganan pengaduan masyarakat melalui sistem Whistle Blowing System (WBS).</p>
                        
                        <div class="service-details">
                            <div class="row text-sm mb-3">
                                <div class="col-6">
                                    <strong>Waktu Layanan:</strong><br>
                                    <small class="text-muted">14 hari kerja</small>
                                </div>
                                <div class="col-6">
                                    <strong>Biaya:</strong><br>
                                    <small class="text-success">Gratis</small>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#serviceModal3">
                            <i class="fas fa-info-circle me-1"></i> Detail Layanan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Service Card 4 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm service-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon bg-success text-white rounded-circle me-3">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Evaluasi Kinerja</h5>
                                <span class="badge bg-success">Audit</span>
                            </div>
                        </div>
                        <p class="card-text">Layanan evaluasi kinerja organisasi dan program kerja untuk peningkatan efektivitas.</p>
                        
                        <div class="service-details">
                            <div class="row text-sm mb-3">
                                <div class="col-6">
                                    <strong>Waktu Layanan:</strong><br>
                                    <small class="text-muted">10 hari kerja</small>
                                </div>
                                <div class="col-6">
                                    <strong>Biaya:</strong><br>
                                    <small class="text-success">Gratis</small>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#serviceModal4">
                            <i class="fas fa-info-circle me-1"></i> Detail Layanan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Service Card 5 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm service-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon bg-secondary text-white rounded-circle me-3">
                                <i class="fas fa-book"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Pelatihan & Bimbingan</h5>
                                <span class="badge bg-secondary">Lainnya</span>
                            </div>
                        </div>
                        <p class="card-text">Layanan pelatihan dan bimbingan teknis terkait pengawasan dan tata kelola pemerintahan.</p>
                        
                        <div class="service-details">
                            <div class="row text-sm mb-3">
                                <div class="col-6">
                                    <strong>Waktu Layanan:</strong><br>
                                    <small class="text-muted">Sesuai jadwal</small>
                                </div>
                                <div class="col-6">
                                    <strong>Biaya:</strong><br>
                                    <small class="text-success">Gratis</small>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#serviceModal5">
                            <i class="fas fa-info-circle me-1"></i> Detail Layanan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Service Card 6 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm service-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon bg-danger text-white rounded-circle me-3">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Investigasi Khusus</h5>
                                <span class="badge bg-danger">Audit</span>
                            </div>
                        </div>
                        <p class="card-text">Layanan investigasi khusus untuk kasus-kasus yang memerlukan penanganan mendalam.</p>
                        
                        <div class="service-details">
                            <div class="row text-sm mb-3">
                                <div class="col-6">
                                    <strong>Waktu Layanan:</strong><br>
                                    <small class="text-muted">30 hari kerja</small>
                                </div>
                                <div class="col-6">
                                    <strong>Biaya:</strong><br>
                                    <small class="text-success">Gratis</small>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#serviceModal6">
                            <i class="fas fa-info-circle me-1"></i> Detail Layanan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h3 class="mb-3">Butuh Bantuan Lebih Lanjut?</h3>
        <p class="lead mb-4">Tim kami siap membantu Anda dengan layanan terbaik</p>
        <a href="{{ route('public.kontak') }}" class="btn btn-primary btn-lg me-3">
            <i class="fas fa-phone me-2"></i> Hubungi Kami
        </a>
        <a href="{{ route('public.wbs') }}" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-exclamation-triangle me-2"></i> Laporkan Masalah
        </a>
    </div>
</section>

<!-- Service Detail Modals -->
@for($i = 1; $i <= 6; $i++)
<div class="modal fade" id="serviceModal{{ $i }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be populated based on the service -->
                <div class="row">
                    <div class="col-md-8">
                        <h6>Deskripsi Lengkap</h6>
                        <p>Detail lengkap tentang layanan ini akan ditampilkan di sini...</p>
                        
                        <h6>Syarat & Ketentuan</h6>
                        <ul>
                            <li>Syarat 1</li>
                            <li>Syarat 2</li>
                            <li>Syarat 3</li>
                        </ul>
                        
                        <h6>Prosedur</h6>
                        <ol>
                            <li>Langkah 1</li>
                            <li>Langkah 2</li>
                            <li>Langkah 3</li>
                        </ol>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>Informasi Layanan</h6>
                                <p><strong>Waktu:</strong> 7 hari kerja</p>
                                <p><strong>Biaya:</strong> Gratis</p>
                                <p><strong>Kontak:</strong> (021) 123-4567</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('public.kontak') }}" class="btn btn-primary">Hubungi Kami</a>
            </div>
        </div>
    </div>
</div>
@endfor

@endsection

@push('styles')
<style>
.service-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.service-card {
    transition: transform 0.3s ease;
}

.service-card:hover {
    transform: translateY(-5px);
}

.hero-section {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}
</style>
@endpush

@push('scripts')
<script>
// Search functionality
document.getElementById('searchPelayanan').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const serviceCards = document.querySelectorAll('.service-card');
    
    serviceCards.forEach(card => {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        const content = card.querySelector('.card-text').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || content.includes(searchTerm)) {
            card.closest('.col-lg-4').style.display = 'block';
        } else {
            card.closest('.col-lg-4').style.display = 'none';
        }
    });
});

// Filter functionality
document.getElementById('filterKategori').addEventListener('change', function() {
    const selectedCategory = this.value.toLowerCase();
    const serviceCards = document.querySelectorAll('.service-card');
    
    serviceCards.forEach(card => {
        const badge = card.querySelector('.badge');
        const category = badge.textContent.toLowerCase();
        
        if (selectedCategory === '' || category === selectedCategory) {
            card.closest('.col-lg-4').style.display = 'block';
        } else {
            card.closest('.col-lg-4').style.display = 'none';
        }
    });
});
</script>
@endpush
