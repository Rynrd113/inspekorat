@extends('layouts.admin')

@section('header', 'Manajemen Galeri')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><span class="text-gray-500">Galeri</span></li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Galeri</h1>
            <p class="text-gray-600 mt-1">Kelola galeri foto dan video</p>
        </div>
        <a href="{{ route('admin.galeri.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah Media
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-images text-gray-400 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">Galeri Foto & Video</h2>
                </div>
                <a href="{{ route('admin.galeri.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Tambah Media
                </a>
            </div>
        </div>
        <div class="px-6 py-4">
            <!-- Filter dan Search -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label for="filterTipe" class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                    <select id="filterTipe" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Tipe</option>
                        <option value="foto">Foto</option>
                        <option value="video">Video</option>
                    </select>
                </div>
                <div>
                    <label for="filterKategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select id="filterKategori" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        <option value="kegiatan">Kegiatan</option>
                        <option value="acara">Acara</option>
                        <option value="fasilitas">Fasilitas</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label for="filterStatus" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non-aktif</option>
                    </select>
                </div>
                <div>
                    <label for="searchGaleri" class="block text-sm font-medium text-gray-700 mb-2">Cari Media</label>
                    <input type="text" id="searchGaleri" placeholder="Cari media..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
                </div>
            </div>

            <!-- Grid View -->
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

            <!-- Pagination -->
            <div class="mt-6 flex items-center justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">1</a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">2</a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">3</a>
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </nav>
            </div>
        </div>
    </div>
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

// Filter functionality
document.getElementById('filterTipe').addEventListener('change', function() {
    console.log('Filter tipe changed:', this.value);
});

document.getElementById('filterKategori').addEventListener('change', function() {
    console.log('Filter kategori changed:', this.value);
});

document.getElementById('filterStatus').addEventListener('change', function() {
    console.log('Filter status changed:', this.value);
});

document.getElementById('searchGaleri').addEventListener('input', function() {
    console.log('Search input:', this.value);
});

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
