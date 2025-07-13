@extends('layouts.admin')

@section('header', 'Manajemen FAQ')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><span class="text-gray-500">FAQ</span></li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen FAQ</h1>
            <p class="text-gray-600 mt-1">Kelola pertanyaan yang sering diajukan</p>
        </div>
        <a href="{{ route('admin.faq.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah FAQ
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-question-circle text-gray-400 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">Frequently Asked Questions</h2>
                </div>
            </div>
        </div>
        <div class="px-6 py-4">
            <!-- Filter dan Search -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="filterKategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select id="filterKategori" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        <option value="umum">Umum</option>
                        <option value="layanan">Layanan</option>
                        <option value="pengaduan">Pengaduan</option>
                        <option value="audit">Audit</option>
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
                    <label for="searchFaq" class="block text-sm font-medium text-gray-700 mb-2">Cari FAQ</label>
                    <input type="text" id="searchFaq" placeholder="Cari FAQ..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Update</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Sample data - replace with actual data -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">Apa itu Inspektorat Papua Tengah?</div>
                                <div class="text-sm text-gray-500">Pertanyaan tentang profil dan tugas Inspektorat...</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Umum</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-900">1</span>
                                    <div class="flex space-x-1">
                                        <button type="button" onclick="moveUp(1)" class="p-1 text-gray-400 hover:text-gray-600" title="Naik">
                                            <i class="fas fa-arrow-up text-xs"></i>
                                        </button>
                                        <button type="button" onclick="moveDown(1)" class="p-1 text-gray-400 hover:text-gray-600" title="Turun">
                                            <i class="fas fa-arrow-down text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">15 Jan 2024</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.faq.show', 1) }}" class="text-blue-600 hover:text-blue-900" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.faq.edit', 1) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
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
                                <div class="text-sm font-medium text-gray-900">Bagaimana cara menyampaikan pengaduan?</div>
                                <div class="text-sm text-gray-500">Prosedur pengaduan melalui sistem WBS...</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pengaduan</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-900">2</span>
                                    <div class="flex space-x-1">
                                        <button type="button" onclick="moveUp(2)" class="p-1 text-gray-400 hover:text-gray-600" title="Naik">
                                            <i class="fas fa-arrow-up text-xs"></i>
                                        </button>
                                        <button type="button" onclick="moveDown(2)" class="p-1 text-gray-400 hover:text-gray-600" title="Turun">
                                            <i class="fas fa-arrow-down text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">12 Jan 2024</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.faq.show', 2) }}" class="text-blue-600 hover:text-blue-900" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.faq.edit', 2) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
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
                                <div class="text-sm font-medium text-gray-900">Apa saja layanan yang tersedia di Inspektorat?</div>
                                <div class="text-sm text-gray-500">Daftar layanan audit dan konsultasi...</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">Layanan</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-900">3</span>
                                    <div class="flex space-x-1">
                                        <button type="button" onclick="moveUp(3)" class="p-1 text-gray-400 hover:text-gray-600" title="Naik">
                                            <i class="fas fa-arrow-up text-xs"></i>
                                        </button>
                                        <button type="button" onclick="moveDown(3)" class="p-1 text-gray-400 hover:text-gray-600" title="Turun">
                                            <i class="fas fa-arrow-down text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">10 Jan 2024</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.faq.show', 3) }}" class="text-blue-600 hover:text-blue-900" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.faq.edit', 3) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="confirmDelete(3)" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">Berapa lama proses audit internal?</div>
                                <div class="text-sm text-gray-500">Informasi tentang waktu proses audit...</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Audit</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Non-aktif</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-900">4</span>
                                    <div class="flex space-x-1">
                                        <button type="button" onclick="moveUp(4)" class="p-1 text-gray-400 hover:text-gray-600" title="Naik">
                                            <i class="fas fa-arrow-up text-xs"></i>
                                        </button>
                                        <button type="button" onclick="moveDown(4)" class="p-1 text-gray-400 hover:text-gray-600" title="Turun">
                                            <i class="fas fa-arrow-down text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">08 Jan 2024</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.faq.show', 4) }}" class="text-blue-600 hover:text-blue-900" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.faq.edit', 4) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="confirmDelete(4)" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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

<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus FAQ ini?')) {
            document.getElementById('deleteForm').action = `/admin/faq/${id}`;
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection
