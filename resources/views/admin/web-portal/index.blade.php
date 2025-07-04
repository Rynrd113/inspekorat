@extends('layouts.admin')

@section('title', 'Kelola Web Portal')

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Web Portal</h1>
            <p class="text-gray-600">Kelola konten dan informasi web portal</p>
        </div>
        <x-button type="button" variant="primary" onclick="showCreateModal()">
            <i class="fas fa-plus mr-2"></i>Tambah Konten
        </x-button>
    </div>

    <!-- Filter dan Search -->
    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                <input type="text" id="search" placeholder="Cari judul atau konten..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                <select id="tipe" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tipe</option>
                    <option value="berita">Berita</option>
                    <option value="pengumuman">Pengumuman</option>
                    <option value="informasi">Informasi</option>
                    <option value="layanan">Layanan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
            <div class="flex items-end">
                <x-button type="button" variant="primary" class="w-full" onclick="filterWebPortal()">
                    <i class="fas fa-search mr-2"></i>Filter
                </x-button>
            </div>
        </div>
    </x-card>

    <!-- Tabel Web Portal -->
    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Judul
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipe
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="web-portal-tbody" class="bg-white divide-y divide-gray-200">
                    <!-- Data akan dimuat via AJAX -->
                </tbody>
            </table>
        </div>
        
        <!-- Loading State -->
        <div id="loading" class="text-center py-8 hidden">
            <i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i>
            <p class="text-gray-500 mt-2">Memuat data...</p>
        </div>
        
        <!-- Empty State -->
        <div id="empty-state" class="text-center py-8 hidden">
            <i class="fas fa-newspaper text-gray-400 text-4xl"></i>
            <p class="text-gray-500 mt-2">Tidak ada konten ditemukan</p>
        </div>
    </x-card>
</div>

<!-- Modal Create/Edit Web Portal -->
<div id="form-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modal-title" class="text-lg font-bold text-gray-900">Tambah Konten</h3>
            <button onclick="closeFormModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="web-portal-form">
            <input type="hidden" id="web-portal-id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
                    <input type="text" id="judul" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe <span class="text-red-500">*</span></label>
                    <select id="tipe_konten" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Pilih Tipe</option>
                        <option value="berita">Berita</option>
                        <option value="pengumuman">Pengumuman</option>
                        <option value="informasi">Informasi</option>
                        <option value="layanan">Layanan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konten <span class="text-red-500">*</span></label>
                    <textarea id="konten" rows="10" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required 
                              placeholder="Masukkan konten artikel..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Link Eksternal</label>
                    <input type="url" id="link_eksternal" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="https://example.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publikasi</label>
                    <input type="date" id="tanggal_publikasi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea id="meta_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              placeholder="Deskripsi singkat untuk SEO..."></textarea>
                </div>
            </div>
            <div class="flex space-x-3">
                <x-button type="button" variant="secondary" class="flex-1" onclick="closeFormModal()">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary" class="flex-1">
                    <span id="submit-text">Simpan</span>
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Web Portal -->
<div id="detail-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Detail Konten</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="detail-content">
            <!-- Detail akan dimuat via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPage = 1;
let isEditing = false;

// Load web portal data
function loadWebPortal(page = 1) {
    const search = document.getElementById('search').value;
    const tipe = document.getElementById('tipe').value;
    const status = document.getElementById('status').value;
    
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('web-portal-tbody').innerHTML = '';
    document.getElementById('empty-state').classList.add('hidden');
    
    const params = new URLSearchParams({
        page: page,
        search: search,
        tipe: tipe,
        is_active: status
    });
    
    fetch(`/api/web-portal?${params}`, {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loading').classList.add('hidden');
        
        if (data.data && data.data.length > 0) {
            displayWebPortal(data.data);
        } else {
            document.getElementById('empty-state').classList.remove('hidden');
        }
    })
    .catch(error => {
        document.getElementById('loading').classList.add('hidden');
        console.error('Error:', error);
        showAlert('Gagal memuat data web portal', 'error');
    });
}

function displayWebPortal(webPortal) {
    const tbody = document.getElementById('web-portal-tbody');
    tbody.innerHTML = webPortal.map(item => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">${item.judul}</div>
                <div class="text-sm text-gray-500 max-w-xs truncate">${item.konten ? item.konten.substring(0, 100) + '...' : 'Tidak ada konten'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getTipeClass(item.tipe)}">
                    ${getTipeText(item.tipe)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${item.tanggal_publikasi ? new Date(item.tanggal_publikasi).toLocaleDateString('id-ID') : '-'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                    item.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                }">
                    ${item.is_active ? 'Aktif' : 'Tidak Aktif'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <button onclick="showDetail(${item.id})" class="text-blue-600 hover:text-blue-900">
                    <i class="fas fa-eye"></i>
                </button>
                <button onclick="editWebPortal(${item.id})" class="text-green-600 hover:text-green-900">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="deleteWebPortal(${item.id})" class="text-red-600 hover:text-red-900">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function getTipeClass(tipe) {
    const classes = {
        'berita': 'bg-blue-100 text-blue-800',
        'pengumuman': 'bg-yellow-100 text-yellow-800',
        'informasi': 'bg-green-100 text-green-800',
        'layanan': 'bg-purple-100 text-purple-800'
    };
    return classes[tipe] || 'bg-gray-100 text-gray-800';
}

function getTipeText(tipe) {
    const texts = {
        'berita': 'Berita',
        'pengumuman': 'Pengumuman',
        'informasi': 'Informasi',
        'layanan': 'Layanan'
    };
    return texts[tipe] || tipe;
}

function filterWebPortal() {
    currentPage = 1;
    loadWebPortal(currentPage);
}

function showCreateModal() {
    isEditing = false;
    document.getElementById('modal-title').textContent = 'Tambah Konten';
    document.getElementById('submit-text').textContent = 'Simpan';
    document.getElementById('web-portal-form').reset();
    document.getElementById('web-portal-id').value = '';
    
    // Set default tanggal publikasi ke hari ini
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal_publikasi').value = today;
    
    document.getElementById('form-modal').classList.remove('hidden');
}

function editWebPortal(id) {
    isEditing = true;
    document.getElementById('modal-title').textContent = 'Edit Konten';
    document.getElementById('submit-text').textContent = 'Update';
    
    fetch(`/api/web-portal/${id}`, {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const item = data.data;
        document.getElementById('web-portal-id').value = item.id;
        document.getElementById('judul').value = item.judul;
        document.getElementById('tipe_konten').value = item.tipe;
        document.getElementById('konten').value = item.konten;
        document.getElementById('link_eksternal').value = item.link_eksternal || '';
        document.getElementById('tanggal_publikasi').value = item.tanggal_publikasi ? 
            item.tanggal_publikasi.split('T')[0] : '';
        document.getElementById('meta_description').value = item.meta_description || '';
        document.getElementById('is_active').value = item.is_active ? '1' : '0';
        
        document.getElementById('form-modal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Gagal memuat data web portal', 'error');
    });
}

function showDetail(id) {
    fetch(`/api/web-portal/${id}`, {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const item = data.data;
        document.getElementById('detail-content').innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Judul</label>
                        <p class="text-sm text-gray-900">${item.judul}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getTipeClass(item.tipe)}">
                            ${getTipeText(item.tipe)}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Publikasi</label>
                        <p class="text-sm text-gray-900">${item.tanggal_publikasi ? 
                            new Date(item.tanggal_publikasi).toLocaleDateString('id-ID') : '-'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                            item.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                        }">
                            ${item.is_active ? 'Aktif' : 'Tidak Aktif'}
                        </span>
                    </div>
                </div>
                ${item.link_eksternal ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700">Link Eksternal</label>
                    <a href="${item.link_eksternal}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">${item.link_eksternal}</a>
                </div>
                ` : ''}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Konten</label>
                    <div class="text-sm text-gray-900 whitespace-pre-wrap border p-3 rounded-md bg-gray-50 max-h-64 overflow-y-auto">${item.konten}</div>
                </div>
                ${item.meta_description ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700">Meta Description</label>
                    <p class="text-sm text-gray-900">${item.meta_description}</p>
                </div>
                ` : ''}
                <div class="text-xs text-gray-500 border-t pt-2">
                    Dibuat: ${new Date(item.created_at).toLocaleString('id-ID')}
                    ${item.updated_at !== item.created_at ? 
                        `<br>Diupdate: ${new Date(item.updated_at).toLocaleString('id-ID')}` : ''}
                </div>
            </div>
        `;
        document.getElementById('detail-modal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Gagal memuat detail konten', 'error');
    });
}

function closeFormModal() {
    document.getElementById('form-modal').classList.add('hidden');
    document.getElementById('web-portal-form').reset();
}

function closeDetailModal() {
    document.getElementById('detail-modal').classList.add('hidden');
}

// Form submit
document.getElementById('web-portal-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        judul: document.getElementById('judul').value,
        tipe: document.getElementById('tipe_konten').value,
        konten: document.getElementById('konten').value,
        link_eksternal: document.getElementById('link_eksternal').value,
        tanggal_publikasi: document.getElementById('tanggal_publikasi').value,
        meta_description: document.getElementById('meta_description').value,
        is_active: document.getElementById('is_active').value === '1'
    };
    
    const url = isEditing ? 
        `/api/web-portal/${document.getElementById('web-portal-id').value}` : 
        '/api/web-portal';
    const method = isEditing ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.data) {
            showAlert(`Konten berhasil ${isEditing ? 'diupdate' : 'ditambahkan'}`, 'success');
            closeFormModal();
            loadWebPortal(currentPage);
        } else {
            showAlert('Gagal menyimpan data', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Gagal menyimpan data', 'error');
    });
});

function deleteWebPortal(id) {
    if (confirm('Apakah Anda yakin ingin menghapus konten ini?')) {
        fetch(`/api/web-portal/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || response.ok) {
                showAlert('Konten berhasil dihapus', 'success');
                loadWebPortal(currentPage);
            } else {
                showAlert('Gagal hapus konten', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Gagal hapus konten', 'error');
        });
    }
}

function showAlert(message, type = 'info') {
    AdminPanel.showAlert(message, type);
}

// Search dengan debounce
let searchTimeout;
document.getElementById('search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        filterWebPortal();
    }, 500);
});

// Load data saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadWebPortal();
});
</script>
@endpush
