@extends('layouts.admin')

@section('title', 'Manajemen FAQ')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen FAQ</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">FAQ</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-question-circle me-1"></i>
                Frequently Asked Questions
            </div>
            <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah FAQ
            </a>
        </div>
        <div class="card-body">
            <!-- Filter dan Search -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <select class="form-select" id="filterKategori">
                        <option value="">Semua Kategori</option>
                        <option value="umum">Umum</option>
                        <option value="layanan">Layanan</option>
                        <option value="pengaduan">Pengaduan</option>
                        <option value="audit">Audit</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non-aktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="searchFaq" placeholder="Cari FAQ...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tabelFaq">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="35%">Pertanyaan</th>
                            <th width="15%">Kategori</th>
                            <th width="10%">Status</th>
                            <th width="10%">Urutan</th>
                            <th width="15%">Tanggal Update</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample data - replace with actual data -->
                        <tr>
                            <td>1</td>
                            <td>
                                <strong>Apa itu Inspektorat Papua Tengah?</strong>
                                <br><small class="text-muted">Pertanyaan tentang profil dan tugas Inspektorat...</small>
                            </td>
                            <td><span class="badge bg-primary">Umum</span></td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">1</span>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-secondary" onclick="moveUp(1)" title="Naik">
                                            <i class="fas fa-arrow-up"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="moveDown(1)" title="Turun">
                                            <i class="fas fa-arrow-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td>15 Jan 2024</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.faq.show', 1) }}" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.faq.edit', 1) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(1)" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                <strong>Bagaimana cara menyampaikan pengaduan?</strong>
                                <br><small class="text-muted">Prosedur pengaduan melalui sistem WBS...</small>
                            </td>
                            <td><span class="badge bg-warning">Pengaduan</span></td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">2</span>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-secondary" onclick="moveUp(2)" title="Naik">
                                            <i class="fas fa-arrow-up"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="moveDown(2)" title="Turun">
                                            <i class="fas fa-arrow-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td>12 Jan 2024</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.faq.show', 2) }}" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.faq.edit', 2) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(2)" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>
                                <strong>Apa saja layanan yang tersedia di Inspektorat?</strong>
                                <br><small class="text-muted">Daftar layanan audit dan konsultasi...</small>
                            </td>
                            <td><span class="badge bg-info">Layanan</span></td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">3</span>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-secondary" onclick="moveUp(3)" title="Naik">
                                            <i class="fas fa-arrow-up"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="moveDown(3)" title="Turun">
                                            <i class="fas fa-arrow-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td>10 Jan 2024</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.faq.show', 3) }}" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.faq.edit', 3) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(3)" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>
                                <strong>Berapa lama proses audit internal?</strong>
                                <br><small class="text-muted">Informasi tentang waktu proses audit...</small>
                            </td>
                            <td><span class="badge bg-success">Audit</span></td>
                            <td><span class="badge bg-secondary">Non-aktif</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">4</span>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-secondary" onclick="moveUp(4)" title="Naik">
                                            <i class="fas fa-arrow-up"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="moveDown(4)" title="Turun">
                                            <i class="fas fa-arrow-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td>08 Jan 2024</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.faq.show', 4) }}" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.faq.edit', 4) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(4)" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus FAQ ini?
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
function confirmDelete(id) {
    document.getElementById('deleteForm').action = `{{ route('admin.faq.index') }}/${id}`;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function moveUp(id) {
    // AJAX call to move item up
    fetch(`{{ route('admin.faq.index') }}/${id}/move-up`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function moveDown(id) {
    // AJAX call to move item down
    fetch(`{{ route('admin.faq.index') }}/${id}/move-down`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Filter functionality
document.getElementById('filterKategori').addEventListener('change', function() {
    // Add filter logic here
});

document.getElementById('filterStatus').addEventListener('change', function() {
    // Add filter logic here
});

document.getElementById('searchFaq').addEventListener('input', function() {
    // Add search logic here
});
</script>
@endpush
@endsection
