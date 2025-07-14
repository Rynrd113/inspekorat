@extends('layouts.admin')

@section('main-content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Dokumen</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-home mr-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-chevron-right mx-2 text-gray-300"></i>
                            <span class="text-gray-600">Dokumen</span>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex items-center space-x-3">
                <x-button 
                    href="{{ route('admin.dokumen.create') }}"
                    variant="primary" 
                    size="md"
                >
                    <i class="fas fa-plus mr-2"></i>Tambah Dokumen
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
                            placeholder="Cari dokumen..."
                            value="{{ request('search') }}"
                            with-icon="true"
                            size="md"
                        />
                    </div>

                    <!-- Filter Fields -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            <option value="peraturan" {{ request('kategori') == 'peraturan' ? 'selected' : '' }}>Peraturan</option>
                            <option value="panduan" {{ request('kategori') == 'panduan' ? 'selected' : '' }}>Panduan</option>
                            <option value="laporan" {{ request('kategori') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                            <option value="formulir" {{ request('kategori') == 'formulir' ? 'selected' : '' }}>Formulir</option>
                            <option value="lainnya" {{ request('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Non-aktif</option>
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
                        onclick="window.location.href='{{ route('admin.dokumen.index') }}'"
                    >
                        <i class="fas fa-undo mr-2"></i>Reset
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Documents List -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Sample data - replace with actual data -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">Peraturan Inspektorat Papua Tengah 2024</div>
                                <div class="text-sm text-gray-500">peraturan_inspektorat_2024.pdf</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Peraturan</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2.5 MB</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">15 Jan 2024</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-900" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-yellow-600 hover:text-yellow-900" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="{{ route('admin.dokumen.edit', 1) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="confirmDelete(1)" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">Panduan Audit Internal</div>
                                <div class="text-sm text-gray-500">panduan_audit_internal.pdf</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Panduan</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1.8 MB</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">12 Jan 2024</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-900" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-yellow-600 hover:text-yellow-900" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="{{ route('admin.dokumen.edit', 2) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="confirmDelete(2)" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">Formulir Pengaduan WBS</div>
                                <div class="text-sm text-gray-500">formulir_pengaduan_wbs.pdf</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Formulir</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0.5 MB</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">10 Jan 2024</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-900" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-yellow-600 hover:text-yellow-900" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="{{ route('admin.dokumen.edit', 3) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="confirmDelete(3)" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    {{-- Add pagination here when connected to real data --}}
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
                                Apakah Anda yakin ingin menghapus dokumen ini?
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
function confirmDelete(id) {
    document.getElementById('deleteForm').action = `{{ route('admin.dokumen.index') }}/${id}`;
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
@endpush
@endsection
