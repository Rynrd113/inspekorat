@extends('layouts.admin')

@section('title', 'Detail Galeri')

@section('header', 'Detail Galeri')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.galeri.index') }}" class="text-blue-600 hover:text-blue-800">Galeri</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Detail</li>
@endsection

@section('main-content')
<div class="space-y-6">

    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-images mr-2 text-blue-600"></i>
                    Detail Galeri
                </h2>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.galeri.edit', $galeri) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <button type="button" onclick="confirmDelete({{ $galeri->id }})" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                    <a href="{{ route('admin.galeri.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Judul</h3>
                                <p class="text-lg font-semibold text-gray-900">{{ $galeri->judul ?? 'Media Galeri' }}</p>
                            </div>
                            @if($galeri->deskripsi)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Deskripsi</h3>
                                <p class="text-gray-900 leading-relaxed">{!! nl2br(e($galeri->deskripsi)) !!}</p>
                            </div>
                            @endif
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Tipe</h3>
                                    @if(($galeri->tipe ?? 'foto') == 'foto')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-image mr-1"></i>Foto
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-video mr-1"></i>Video
                                        </span>
                                    @endif
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Kategori</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ ucfirst($galeri->kategori ?? 'kegiatan') }}
                                    </span>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                                    @if(($galeri->status ?? 'aktif') == 'aktif')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Non-aktif
                                        </span>
                                    @endif
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Upload</h3>
                                    <p class="text-gray-900">{{ isset($galeri->created_at) ? $galeri->created_at->format('d/m/Y H:i') : date('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @if($galeri->tanggal_ambil)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Pengambilan</h3>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($galeri->tanggal_ambil)->format('d/m/Y') }}</p>
                            </div>
                            @endif
                            @if($galeri->tags)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Tags</h3>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(explode(',', $galeri->tags) as $tag)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    @if(($galeri->tipe ?? 'foto') == 'foto')
                        @if($galeri->file_path)
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $galeri->file_path) }}" 
                                     alt="{{ $galeri->judul }}" 
                                     class="w-full max-w-md h-auto rounded-lg border border-gray-300 mx-auto">
                                <p class="text-sm text-gray-500 mt-2">{{ $galeri->judul }}</p>
                            </div>
                        @else
                            <div class="text-center">
                                <div class="w-full max-w-md h-64 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-300 mx-auto">
                                    <div class="text-gray-400">
                                        <i class="fas fa-image text-4xl mb-2"></i>
                                        <p class="text-sm">Tidak ada foto</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        @if($galeri->url_video)
                            <div class="text-center">
                                <div class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center border border-gray-300">
                                    <a href="{{ $galeri->url_video }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                        <i class="fas fa-play mr-2"></i>Tonton Video
                                    </a>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Video eksternal: {{ $galeri->url_video }}</p>
                            </div>
                        @elseif($galeri->file_path)
                            <div class="text-center">
                                <video controls class="w-full max-w-md h-auto rounded-lg border border-gray-300 mx-auto">
                                    <source src="{{ asset('storage/' . $galeri->file_path) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <p class="text-sm text-gray-500 mt-2">{{ $galeri->judul }}</p>
                            </div>
                        @else
                            <div class="text-center">
                                <div class="w-full max-w-md h-64 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-300 mx-auto">
                                    <div class="text-gray-400">
                                        <i class="fas fa-video text-4xl mb-2"></i>
                                        <p class="text-sm">Tidak ada video</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($galeri->thumbnail)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500 mb-2">Thumbnail:</p>
                                <img src="{{ asset('storage/' . $galeri->thumbnail) }}" 
                                     alt="Thumbnail" 
                                     class="w-32 h-32 object-cover rounded-lg border border-gray-300 mx-auto">
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <a href="{{ route('admin.galeri.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.galeri.edit', $galeri) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <button type="button" onclick="confirmDelete({{ $galeri->id }})" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Konfirmasi Hapus
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus media galeri "<strong>{{ $galeri->judul ?? 'ini' }}</strong>"?
                                <br><br>
                                <small class="text-gray-400">File terkait juga akan dihapus dan tindakan ini tidak dapat dibatalkan.</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    const baseUrl = "{{ route('admin.galeri.destroy', ':id') }}";
    document.getElementById('deleteForm').action = baseUrl.replace(':id', id);
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
                                <tr>
                                    <th>Dibuat Oleh</th>
                                    <td>{{ $galeri->creator->name ?? 'System' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ $galeri->created_at ? $galeri->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                @if($galeri->updated_at)
                                <tr>
                                    <th>Terakhir Diupdate</th>
                                    <td>{{ $galeri->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-4">
                            <!-- Media Preview -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Media:</strong></label>
                                <div class="text-center">
                                    @if($galeri->file_media)
                                        @if($galeri->kategori == 'foto')
                                            <img src="{{ Storage::url($galeri->file_media) }}" 
                                                 alt="{{ $galeri->judul }}" 
                                                 class="img-fluid rounded shadow" 
                                                 style="max-height: 300px;">
                                        @elseif($galeri->kategori == 'video')
                                            <video controls class="img-fluid rounded shadow" style="max-height: 300px;">
                                                <source src="{{ Storage::url($galeri->file_media) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @endif
                                    @elseif($galeri->thumbnail)
                                        <img src="{{ Storage::url($galeri->thumbnail) }}" 
                                             alt="Thumbnail {{ $galeri->judul }}" 
                                             class="img-fluid rounded shadow" 
                                             style="max-height: 300px;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                        <small class="text-muted">Tidak ada media</small>
                                    @endif
                                </div>
                            </div>
                            
                            @if($galeri->thumbnail && $galeri->file_media)
                            <div class="mb-3">
                                <label class="form-label"><strong>Thumbnail:</strong></label>
                                <div class="text-center">
                                    <img src="{{ Storage::url($galeri->thumbnail) }}" 
                                         alt="Thumbnail {{ $galeri->judul }}" 
                                         class="img-fluid rounded shadow" 
                                         style="max-height: 150px;">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>
                        <div>
                            <a href="{{ route('admin.galeri.edit', $galeri) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Galeri
                            </a>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $galeri->id }})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus item galeri ini? File media terkait juga akan dihapus dan tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    document.getElementById('deleteForm').action = '{{ route("admin.galeri.destroy", "") }}/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
