@extends('layouts.admin')

@section('title', 'Kelola Pengaduan')

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Pengaduan</h1>
            <p class="text-gray-600">Kelola semua pengaduan masyarakat</p>
        </div>
    </div>

    <!-- Filter dan Search -->
    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                <input type="text" id="search" placeholder="Cari nama atau subjek..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="flex items-end">
                <x-button type="button" variant="primary" class="w-full" onclick="filterPengaduan()">
                    <i class="fas fa-search mr-2"></i>Filter
                </x-button>
            </div>
        </div>
    </x-card>

    <!-- Tabel Pengaduan -->
    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subjek
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="pengaduan-tbody" class="bg-white divide-y divide-gray-200">
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
            <i class="fas fa-inbox text-gray-400 text-4xl"></i>
            <p class="text-gray-500 mt-2">Tidak ada pengaduan ditemukan</p>
        </div>
    </x-card>
</div>

<!-- Modal Detail Pengaduan -->
<div id="detail-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Detail Pengaduan</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="detail-content">
            <!-- Detail akan dimuat via AJAX -->
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Update Status</h3>
            <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="status-form">
            <input type="hidden" id="pengaduan-id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="new-status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="pending">Pending</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggapan Admin</label>
                <textarea id="admin-notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                          placeholder="Tambahkan tanggapan untuk pengadu..."></textarea>
            </div>
            <div class="flex space-x-3">
                <x-button type="button" variant="secondary" class="flex-1" onclick="closeStatusModal()">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary" class="flex-1">
                    Update Status
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPage = 1;
let totalPages = 1;

// Load pengaduan data
function loadPengaduan(page = 1) {
    const search = document.getElementById('search').value;
    const status = document.getElementById('status').value;
    
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('pengaduan-tbody').innerHTML = '';
    document.getElementById('empty-state').classList.add('hidden');
    
    const params = new URLSearchParams({
        page: page,
        search: search,
        status: status
    });
    
    fetch(`/api/pengaduan?${params}`, {
        headers: AdminPanel.getHeaders()
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loading').classList.add('hidden');
        
        if (data.data && data.data.length > 0) {
            displayPengaduan(data.data);
            updatePagination(data.meta || data);
        } else {
            document.getElementById('empty-state').classList.remove('hidden');
        }
    })
    .catch(error => {
        document.getElementById('loading').classList.add('hidden');
        console.error('Error:', error);
        showAlert('Gagal memuat data pengaduan', 'error');
    });
}

function displayPengaduan(pengaduan) {
    const tbody = document.getElementById('pengaduan-tbody');
    tbody.innerHTML = pengaduan.map(item => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${new Date(item.created_at).toLocaleDateString('id-ID')}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${item.nama_pengadu}</div>
                <div class="text-sm text-gray-500">${item.email}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900 max-w-xs truncate">${item.subjek}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusClass(item.status)}">
                    ${getStatusText(item.status)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <button onclick="showDetail(${item.id})" class="text-blue-600 hover:text-blue-900">
                    <i class="fas fa-eye"></i>
                </button>
                <button onclick="showStatusModal(${item.id}, '${item.status}')" class="text-green-600 hover:text-green-900">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="deletePengaduan(${item.id})" class="text-red-600 hover:text-red-900">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function getStatusClass(status) {
    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'proses': 'bg-blue-100 text-blue-800',
        'selesai': 'bg-green-100 text-green-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function getStatusText(status) {
    const texts = {
        'pending': 'Pending',
        'proses': 'Proses',
        'selesai': 'Selesai'
    };
    return texts[status] || status;
}

function filterPengaduan() {
    currentPage = 1;
    loadPengaduan(currentPage);
}

function showDetail(id) {
    fetch(`/api/pengaduan/${id}`, {
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
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <p class="text-sm text-gray-900">${item.nama_pengadu}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="text-sm text-gray-900">${item.email}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telepon</label>
                        <p class="text-sm text-gray-900">${item.telepon || '-'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <p class="text-sm text-gray-900">${item.kategori}</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subjek</label>
                    <p class="text-sm text-gray-900">${item.subjek}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pesan</label>
                    <p class="text-sm text-gray-900 whitespace-pre-wrap">${item.isi_pengaduan}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusClass(item.status)}">
                        ${getStatusText(item.status)}
                    </span>
                </div>
                ${item.tanggapan ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggapan Admin</label>
                    <p class="text-sm text-gray-900 whitespace-pre-wrap">${item.tanggapan}</p>
                </div>
                ` : ''}
                <div class="text-xs text-gray-500">
                    Dibuat: ${new Date(item.created_at).toLocaleString('id-ID')}
                </div>
            </div>
        `;
        document.getElementById('detail-modal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Gagal memuat detail pengaduan', 'error');
    });
}

function showStatusModal(id, currentStatus) {
    document.getElementById('pengaduan-id').value = id;
    document.getElementById('new-status').value = currentStatus;
    document.getElementById('status-modal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detail-modal').classList.add('hidden');
}

function closeStatusModal() {
    document.getElementById('status-modal').classList.add('hidden');
    document.getElementById('status-form').reset();
}

// Update status form
document.getElementById('status-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('pengaduan-id').value;
    const status = document.getElementById('new-status').value;
    const adminNotes = document.getElementById('admin-notes').value;
    
    fetch(`/api/pengaduan/${id}`, {
        method: 'PUT',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: status,
            tanggapan: adminNotes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.data) {
            showAlert('Status pengaduan berhasil diupdate', 'success');
            closeStatusModal();
            loadPengaduan(currentPage);
        } else {
            showAlert('Gagal update status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Gagal update status', 'error');
    });
});

function deletePengaduan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')) {
        fetch(`/api/pengaduan/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || response.ok) {
                showAlert('Pengaduan berhasil dihapus', 'success');
                loadPengaduan(currentPage);
            } else {
                showAlert('Gagal hapus pengaduan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Gagal hapus pengaduan', 'error');
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
        filterPengaduan();
    }, 500);
});

// Load data saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadPengaduan();
});
</script>
@endpush
