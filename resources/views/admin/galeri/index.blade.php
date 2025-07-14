@extends('layouts.admin')

@section('main-content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Galeri</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-home mr-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-chevron-right mx-2 text-gray-300"></i>
                            <span class="text-gray-600">Galeri</span>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex items-center space-x-3">
                <x-button 
                    href="{{ route('admin.galeri.create') }}"
                    variant="primary" 
                    size="md"
                >
                    <i class="fas fa-plus mr-2"></i>Tambah Media
                </x-button>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <x-search-input 
                            name="search"
                            placeholder="Cari media..."
                            value="{{ request('search') }}"
                            with-icon="true"
                            size="md"
                        />
                    </div>

                    <!-- Filter Fields -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                        <select name="tipe" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Tipe</option>
                            <option value="foto" {{ request('tipe') == 'foto' ? 'selected' : '' }}>Foto</option>
                            <option value="video" {{ request('tipe') == 'video' ? 'selected' : '' }}>Video</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            <option value="kegiatan" {{ request('kategori') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                            <option value="acara" {{ request('kategori') == 'acara' ? 'selected' : '' }}>Acara</option>
                            <option value="fasilitas" {{ request('kategori') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                            <option value="lainnya" {{ request('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    <x-button type="submit" variant="primary" size="md">
                        <i class="fas fa-search mr-2"></i>Cari
                    </x-button>
                    
                    <x-button 
                        type="button" 
                        variant="secondary" 
                        size="md"
                        onclick="window.location.href='{{ route('admin.galeri.index') }}'"
                    >
                        <i class="fas fa-undo mr-2"></i>Reset
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Sample data - replace with actual data -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://via.placeholder.com/300x200?text=Kegiatan+Audit" class="w-full h-48 object-cover" alt="Kegiatan Audit">
                        <div class="absolute top-2 left-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Foto</span>
                        </div>
                        <div class="absolute top-2 right-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kegiatan Audit Internal</h3>
                        <p class="text-sm text-gray-600 mb-3">Dokumentasi kegiatan audit internal tahun 2024</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">15 Jan 2024</span>
                            <div class="flex space-x-2">
                                <button type="button" onclick="viewMedia('foto', 'https://via.placeholder.com/800x600?text=Kegiatan+Audit')" class="text-blue-600 hover:text-blue-900" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.galeri.edit', 1) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" onclick="confirmDelete(1)" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <div class="bg-gray-800 flex items-center justify-center h-48">
                            <i class="fas fa-play-circle text-white text-4xl"></i>
                        </div>
                        <div class="absolute top-2 left-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">Video</span>
                        </div>
                        <div class="absolute top-2 right-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Sosialisasi WBS</h3>
                        <p class="text-sm text-gray-600 mb-3">Video sosialisasi Whistle Blowing System</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">10 Jan 2024</span>
                            <div class="flex space-x-2">
                                <button type="button" onclick="viewMedia('video', '#')" class="text-blue-600 hover:text-blue-900" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.galeri.edit', 2) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" onclick="confirmDelete(2)" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://via.placeholder.com/300x200?text=Fasilitas+Kantor" class="w-full h-48 object-cover" alt="Fasilitas Kantor">
                        <div class="absolute top-2 left-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Foto</span>
                        </div>
                        <div class="absolute top-2 right-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Fasilitas Kantor</h3>
                        <p class="text-sm text-gray-600 mb-3">Dokumentasi fasilitas kantor Inspektorat</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">08 Jan 2024</span>
                            <div class="flex space-x-2">
                                <button type="button" onclick="viewMedia('foto', 'https://via.placeholder.com/800x600?text=Fasilitas+Kantor')" class="text-blue-600 hover:text-blue-900" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.galeri.edit', 3) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" onclick="confirmDelete(3)" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    {{-- Add pagination here when connected to real data --}}
</div>

<!-- Media View Modal -->
<div id="mediaModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Media Preview
                    </h3>
                    <button type="button" onclick="closeMediaModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="mediaContent" class="flex items-center justify-center">
                    <!-- Media content will be loaded here -->
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
                                Apakah Anda yakin ingin menghapus media ini?
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                Tindakan ini tidak dapat dibatalkan.
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

@push('scripts')
<script>
function viewMedia(type, src) {
    const mediaContent = document.getElementById('mediaContent');
    const modal = document.getElementById('mediaModal');
    
    if (type === 'foto') {
        mediaContent.innerHTML = `<img src="${src}" class="max-w-full max-h-96 object-contain" alt="Media">`;
    } else if (type === 'video') {
        mediaContent.innerHTML = `<video controls class="max-w-full max-h-96"><source src="${src}" type="video/mp4">Your browser does not support the video tag.</video>`;
    }
    
    modal.classList.remove('hidden');
}

function closeMediaModal() {
    document.getElementById('mediaModal').classList.add('hidden');
}

function confirmDelete(id) {
    document.getElementById('deleteForm').action = `{{ route('admin.galeri.index') }}/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}


// Close modals when clicking outside
document.getElementById('mediaModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMediaModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection
