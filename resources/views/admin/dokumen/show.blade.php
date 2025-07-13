@extends('layouts.admin')

@section('title', 'Detail Dokumen')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detail Dokumen</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
                <li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
                <li><a href="{{ route('admin.dokumen.index') }}" class="text-blue-600 hover:text-blue-800">Dokumen</a></li>
                <li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
                <li class="text-gray-600">Detail</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-file-alt mr-2 text-blue-600"></i>
                    Detail Dokumen
                </h2>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.dokumen.edit', $dokumen) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    @if($dokumen->file_dokumen)
                    <a href="{{ route('admin.dokumen.download', $dokumen) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-download mr-2"></i>Download
                    </a>
                    @endif
                    <a href="{{ route('admin.dokumen.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Judul Dokumen</h3>
                                <p class="text-lg font-semibold text-gray-900">{{ $dokumen->judul ?? 'Peraturan Inspektorat Papua Tengah' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Kategori</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($dokumen->kategori ?? 'peraturan') }}
                                </span>
                            </div>
                            @if($dokumen->nomor_dokumen)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Nomor Dokumen</h3>
                                <p class="text-gray-900">{{ $dokumen->nomor_dokumen }}</p>
                            </div>
                            @endif
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Dokumen</h3>
                                <p class="text-gray-900">{{ $dokumen->tanggal_dokumen ? \Carbon\Carbon::parse($dokumen->tanggal_dokumen)->format('d/m/Y') : 'Belum ditentukan' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                                @if(($dokumen->status ?? 1) == 1)
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
                                <p class="text-gray-900">{{ isset($dokumen->created_at) ? $dokumen->created_at->format('d/m/Y H:i') : date('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    @if($dokumen->file_cover)
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Cover Dokumen</h3>
                            <img src="{{ asset('storage/' . $dokumen->file_cover) }}" 
                                 alt="Cover Dokumen" 
                                 class="w-full h-64 object-cover rounded-lg border border-gray-300">
                        </div>
                    @endif
                    
                    @if($dokumen->file_dokumen)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">File Dokumen</h3>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                    <span class="text-sm text-gray-700">{{ basename($dokumen->file_dokumen) }}</span>
                                </div>
                                <a href="{{ route('admin.dokumen.download', $dokumen) }}" 
                                   class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($dokumen->deskripsi)
            <div class="mt-6">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Deskripsi</h3>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $dokumen->deskripsi }}
                    </p>
                </div>
            </div>
            @endif

            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <a href="{{ route('admin.dokumen.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.dokumen.edit', $dokumen) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <button type="button" onclick="confirmDelete({{ $dokumen->id }})" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
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
                                Apakah Anda yakin ingin menghapus dokumen ini? File terkait juga akan dihapus dan tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST" style="display: inline;">
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
    const baseUrl = "{{ route('admin.dokumen.destroy', ':id') }}";
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
