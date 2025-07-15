@extends('layouts.admin')

@section('title', 'Detail Dokumen')

@section('header', 'Detail Dokumen')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.dokumen.index') }}" class="text-blue-600 hover:text-blue-800">Dokumen</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Detail</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $dokumen->nama_dokumen ?? 'Detail Dokumen' }}</h1>
            <p class="text-gray-600">Informasi lengkap dokumen</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.dokumen.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
            @if(auth()->user()->hasAnyRole(['admin_dokumen', 'service_manager', 'admin', 'superadmin']))
            <a href="{{ route('admin.dokumen.edit', $dokumen->id ?? 1) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            @endif
        </div>
    </div>

    <!-- Document Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Dokumen</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Dokumen</label>
                        <p class="text-sm text-gray-900">{{ $dokumen->nama_dokumen ?? 'Contoh Dokumen' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <p class="text-sm text-gray-900">{{ $dokumen->deskripsi ?? 'Deskripsi dokumen akan ditampilkan di sini' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konten</label>
                        <div class="prose prose-sm max-w-none">
                            {!! $dokumen->konten ?? '<p>Konten dokumen akan ditampilkan di sini</p>' !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Attachment -->
            @if(isset($dokumen->file_path))
            <div class="bg-white rounded-lg shadow-md border border-gray-200 mt-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">File Dokumen</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-sm font-medium text-gray-900">{{ basename($dokumen->file_path) }}</h4>
                            <p class="text-sm text-gray-500">{{ $dokumen->ukuran_file ?? 'Unknown size' }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ asset('storage/' . $dokumen->file_path) }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat
                            </a>
                            <a href="{{ asset('storage/' . $dokumen->file_path) }}" 
                               download
                               class="inline-flex items-center px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                                <i class="fas fa-download mr-2"></i>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Document Info -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if(($dokumen->kategori ?? 'peraturan') == 'peraturan') bg-blue-100 text-blue-800
                            @elseif(($dokumen->kategori ?? 'peraturan') == 'sop') bg-green-100 text-green-800
                            @elseif(($dokumen->kategori ?? 'peraturan') == 'formulir') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($dokumen->kategori ?? 'Peraturan') }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if(($dokumen->status ?? 'aktif') == 'aktif') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($dokumen->status ?? 'Aktif') }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dibuat</label>
                        <p class="text-sm text-gray-900">{{ $dokumen->created_at->format('d F Y H:i') ?? date('d F Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diperbarui</label>
                        <p class="text-sm text-gray-900">{{ $dokumen->updated_at->format('d F Y H:i') ?? date('d F Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dibuat oleh</label>
                        <p class="text-sm text-gray-900">{{ $dokumen->user->name ?? 'Admin' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if(auth()->user()->hasAnyRole(['admin_dokumen', 'service_manager', 'admin', 'superadmin']))
                    <a href="{{ route('admin.dokumen.edit', $dokumen->id ?? 1) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Dokumen
                    </a>
                    
                    <button onclick="confirmDelete({{ $dokumen->id ?? 1 }})" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Dokumen
                    </button>
                    @endif
                    
                    <a href="{{ route('admin.dokumen.index') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Statistik</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Views</span>
                        <span class="text-sm font-medium text-gray-900">{{ $dokumen->views ?? '0' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Downloads</span>
                        <span class="text-sm font-medium text-gray-900">{{ $dokumen->downloads ?? '0' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Ukuran File</span>
                        <span class="text-sm font-medium text-gray-900">{{ $dokumen->ukuran_file ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
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
                            Hapus Dokumen
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus dokumen ini? Tindakan ini tidak dapat dibatalkan.
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
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/admin/dokumen/${id}`;
    modal.classList.remove('hidden');
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
