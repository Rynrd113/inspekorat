@extends('layouts.admin')

@section('header', 'Portal OPD')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><span class="text-gray-500">Portal OPD</span></li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Portal OPD</h1>
            <p class="text-gray-600 mt-1">Kelola data Organisasi Perangkat Daerah</p>
        </div>
        @if(auth()->user()->hasAnyRole(['admin_portal_opd', 'admin', 'super_admin']))
        <div class="flex items-center space-x-3">
            <button class="sync-data-btn inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors"
                    onclick="syncPortalOpdData()">
                <i class="fas fa-sync mr-2"></i>Sync Data
            </button>
            <a href="{{ route('admin.portal-opd.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah OPD
            </a>
        </div>
        @endif
    </div>

    <!-- Search and Filter -->
    <x-card>
        <form method="GET" class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Nama OPD, singkatan, atau kepala OPD..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit"
                            class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    <a href="{{ route('admin.portal-opd.index') }}"
                       class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </x-card>

    <!-- Table -->
    <x-card>
        <div class="data-table overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OPD</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepala OPD</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($portalOpds as $opd)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $opd->logo_url }}" alt="{{ $opd->nama_opd }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $opd->nama_opd }}</div>
                                    @if($opd->singkatan)
                                    <div class="text-sm text-gray-500">{{ $opd->singkatan }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $opd->kepala_opd ?: '-' }}</div>
                            @if($opd->nip_kepala)
                            <div class="text-sm text-gray-500">NIP: {{ $opd->nip_kepala }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $opd->email ?: '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $opd->telepon ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($opd->status)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Nonaktif
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.portal-opd.show', $opd) }}"
                                   class="text-blue-600 hover:text-blue-900"
                                   title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->hasAnyRole(['admin_portal_opd', 'admin', 'super_admin']))
                                <a href="{{ route('admin.portal-opd.edit', $opd) }}"
                                   class="text-indigo-600 hover:text-indigo-900"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(auth()->user()->hasAnyRole(['admin_portal_opd', 'admin', 'super_admin']))
                                <form action="{{ route('admin.portal-opd.destroy', $opd) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900"
                                            title="Hapus"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus OPD ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data OPD ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($portalOpds->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $portalOpds->links() }}
        </div>
        @endif
    </x-card>
    
    <!-- Sync Progress Indicator -->
    <div class="sync-progress hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <i class="fas fa-sync fa-spin text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-2">Syncing Data</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Please wait while we synchronize Portal OPD data...
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function syncPortalOpdData() {
    const syncBtn = document.querySelector('.sync-data-btn');
    const syncProgress = document.querySelector('.sync-progress');
    
    // Show progress
    syncProgress.classList.remove('hidden');
    syncBtn.disabled = true;
    syncBtn.innerHTML = '<i class="fas fa-spin fa-spinner mr-2"></i>Syncing...';
    
    // Make AJAX call to sync endpoint
    fetch('/admin/portal-opd/sync', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        syncProgress.classList.add('hidden');
        syncBtn.disabled = false;
        syncBtn.innerHTML = '<i class="fas fa-sync mr-2"></i>Sync Data';
        
        // Show success message
        const successMsg = document.createElement('div');
        successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg z-50';
        successMsg.textContent = data.message || 'Data synchronization completed';
        document.body.appendChild(successMsg);
        
        setTimeout(() => {
            successMsg.remove();
        }, 3000);
        
        // Reload page to show updated data
        window.location.reload();
    })
    .catch(error => {
        syncProgress.classList.add('hidden');
        syncBtn.disabled = false;
        syncBtn.innerHTML = '<i class="fas fa-sync mr-2"></i>Sync Data';
        
        console.error('Sync error:', error);
        
        // Show error message
        const errorMsg = document.createElement('div');
        errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-md shadow-lg z-50';
        errorMsg.textContent = 'Failed to synchronize data';
        document.body.appendChild(errorMsg);
        
        setTimeout(() => {
            errorMsg.remove();
        }, 3000);
    });
}
</script>
@endsection
