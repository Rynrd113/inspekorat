@extends('layouts.admin')

@section('title', 'Manajemen Galeri')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Galeri</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Galeri</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-images me-1"></i>
                Galeri Foto & Video
            </div>
            <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Media
            </a>
        </div>
        <div class="card-body">
            <!-- Filter dan Search -->
            <div class="row mb-3">
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
                    <select class="form-select" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non-aktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="searchGaleri" placeholder="Cari media...">
                </div>
            </div>

            <!-- Grid View -->
            <div class="row" id="galeriGrid">
                <!-- Sample data - replace with actual data -->
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            <img src="https://via.placeholder.com/300x200?text=Kegiatan+Audit" class="card-img-top" alt="Kegiatan Audit" style="height: 200px; object-fit: cover;">
                            <span class="badge bg-primary position-absolute top-0 start-0 m-2">Foto</span>
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">Aktif</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Kegiatan Audit Internal</h6>
                            <p class="card-text small text-muted">Dokumentasi kegiatan audit internal tahun 2024</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">15 Jan 2024</small>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-info" onclick="viewMedia('foto', 'https://via.placeholder.com/800x600?text=Kegiatan+Audit')" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.galeri.edit', 1) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(1)" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-play-circle fa-3x text-white"></i>
                            </div>
                            <span class="badge bg-info position-absolute top-0 start-0 m-2">Video</span>
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">Aktif</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Sosialisasi WBS</h6>
                            <p class="card-text small text-muted">Video sosialisasi Whistle Blowing System</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">10 Jan 2024</small>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-info" onclick="viewMedia('video', '#')" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.galeri.edit', 2) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(2)" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            <img src="https://via.placeholder.com/300x200?text=Fasilitas+Kantor" class="card-img-top" alt="Fasilitas Kantor" style="height: 200px; object-fit: cover;">
                            <span class="badge bg-primary position-absolute top-0 start-0 m-2">Foto</span>
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">Aktif</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Fasilitas Kantor</h6>
                            <p class="card-text small text-muted">Ruang kerja Inspektorat Papua Tengah</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">05 Jan 2024</small>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-info" onclick="viewMedia('foto', 'https://via.placeholder.com/800x600?text=Fasilitas+Kantor')" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.galeri.edit', 3) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(3)" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            <img src="https://via.placeholder.com/300x200?text=Rapat+Koordinasi" class="card-img-top" alt="Rapat Koordinasi" style="height: 200px; object-fit: cover;">
                            <span class="badge bg-primary position-absolute top-0 start-0 m-2">Foto</span>
                            <span class="badge bg-secondary position-absolute top-0 end-0 m-2">Non-aktif</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Rapat Koordinasi</h6>
                            <p class="card-text small text-muted">Rapat koordinasi dengan OPD terkait</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">01 Jan 2024</small>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-info" onclick="viewMedia('foto', 'https://via.placeholder.com/800x600?text=Rapat+Koordinasi')" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.galeri.edit', 4) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(4)" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
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
    </div>
</div>

<!-- View Media Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Preview Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="mediaPreview">
                <!-- Media content will be inserted here -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus media ini?
                <br><br>
                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewMedia(type, src) {
    const mediaPreview = document.getElementById('mediaPreview');
    
    if (type === 'foto') {
        mediaPreview.innerHTML = `<img src="${src}" class="img-fluid" alt="Preview">`;
    } else if (type === 'video') {
        mediaPreview.innerHTML = `
            <video controls class="w-100" style="max-height: 400px;">
                <source src="${src}" type="video/mp4">
                Browser Anda tidak mendukung tag video.
            </video>
        `;
    }
    
    var viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
    viewModal.show();
}

function confirmDelete(id) {
    document.getElementById('deleteForm').action = `{{ route('admin.galeri.index') }}/${id}`;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Filter functionality
document.getElementById('filterTipe').addEventListener('change', function() {
    // Add filter logic here
});

document.getElementById('filterKategori').addEventListener('change', function() {
    // Add filter logic here
});

document.getElementById('filterStatus').addEventListener('change', function() {
    // Add filter logic here
});

document.getElementById('searchGaleri').addEventListener('input', function() {
    // Add search logic here
});
</script>
@endpush
@endsection
