@extends('layouts.admin')

@section('title', 'Manajemen Dokumen')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Dokumen</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Dokumen</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-folder-open me-1"></i>
                Daftar Dokumen
            </div>
            <a href="{{ route('admin.dokumen.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Dokumen
            </a>
        </div>
        <div class="card-body">
            <!-- Filter dan Search -->
            <div class="row mb-3">
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
                    <select class="form-select" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non-aktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="searchDokumen" placeholder="Cari dokumen...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tabelDokumen">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Judul</th>
                            <th width="15%">Kategori</th>
                            <th width="15%">Ukuran</th>
                            <th width="10%">Status</th>
                            <th width="15%">Tanggal Upload</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample data - replace with actual data -->
                        <tr>
                            <td>1</td>
                            <td>
                                <strong>Peraturan Inspektorat Papua Tengah 2024</strong>
                                <br><small class="text-muted">peraturan_inspektorat_2024.pdf</small>
                            </td>
                            <td><span class="badge bg-primary">Peraturan</span></td>
                            <td>2.5 MB</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>15 Jan 2024</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="#" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-warning" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="{{ route('admin.dokumen.edit', 1) }}" class="btn btn-sm btn-primary" title="Edit">
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
                                <strong>Panduan Audit Internal</strong>
                                <br><small class="text-muted">panduan_audit_internal.pdf</small>
                            </td>
                            <td><span class="badge bg-info">Panduan</span></td>
                            <td>1.8 MB</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>10 Jan 2024</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="#" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-warning" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="{{ route('admin.dokumen.edit', 2) }}" class="btn btn-sm btn-primary" title="Edit">
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
                                <strong>Formulir Pengaduan Masyarakat</strong>
                                <br><small class="text-muted">form_pengaduan.pdf</small>
                            </td>
                            <td><span class="badge bg-secondary">Formulir</span></td>
                            <td>156 KB</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>05 Jan 2024</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="#" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-warning" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="{{ route('admin.dokumen.edit', 3) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(3)" title="Hapus">
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
                Apakah Anda yakin ingin menghapus dokumen ini?
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
    document.getElementById('deleteForm').action = `{{ route('admin.dokumen.index') }}/${id}`;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Filter functionality
document.getElementById('filterKategori').addEventListener('change', function() {
    // Add filter logic here
});

document.getElementById('filterStatus').addEventListener('change', function() {
    // Add filter logic here
});

document.getElementById('searchDokumen').addEventListener('input', function() {
    // Add search logic here
});
</script>
@endpush
@endsection
