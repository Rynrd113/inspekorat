@extends('layouts.admin')

@section('title', 'Kelola Pengaduan')

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Pengaduan</h1>
            <p class="text-gray-600">Kelola semua pengaduan masyarakat ({{ $pengaduans->total() }} total)</p>
        </div>
    </div>

    <!-- Filter dan Search -->
    <x-card>
        <form method="GET" action="{{ route('admin.pengaduan.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau subjek..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <x-button type="submit" variant="primary" class="flex-1">
                    <i class="fas fa-search mr-2"></i>Filter
                </x-button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.pengaduan.index') }}"
                       class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </x-card>

    <!-- Tabel Pengaduan -->
    <x-card>
        @if($pengaduans->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjek</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengaduans as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->nama_pengadu }}</div>
                            <div class="text-sm text-gray-500">{{ $item->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate">{{ $item->subjek }}</div>
                            @if($item->kategori)
                                <div class="text-xs text-gray-400">{{ ucfirst($item->kategori) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = match($item->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'proses'  => 'bg-blue-100 text-blue-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                    default   => 'bg-gray-100 text-gray-800',
                                };
                                $statusLabel = match($item->status) {
                                    'pending' => 'Pending',
                                    'proses'  => 'Proses',
                                    'selesai' => 'Selesai',
                                    default   => ucfirst($item->status),
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="showDetail({{ $item->id }})"
                                    class="text-blue-600 hover:text-blue-900" title="Lihat detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="showStatusModal({{ $item->id }}, '{{ $item->status }}')"
                                    class="text-green-600 hover:text-green-900" title="Update status">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.pengaduan.destroy', $item) }}"
                                  class="inline" onsubmit="return confirm('Hapus pengaduan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $pengaduans->withQueryString()->links() }}
        </div>

        @else
        <div class="text-center py-8">
            <i class="fas fa-inbox text-gray-400 text-4xl"></i>
            <p class="text-gray-500 mt-2">Tidak ada pengaduan ditemukan</p>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.pengaduan.index') }}" class="mt-3 inline-block text-sm text-blue-600 hover:underline">
                    Lihat semua pengaduan
                </a>
            @endif
        </div>
        @endif
    </x-card>
</div>

<!-- Modal Detail Pengaduan -->
<div id="detail-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Detail Pengaduan</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="detail-content"></div>
    </div>
</div>

<!-- Modal Update Status -->
<div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Update Status</h3>
            <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="status-form" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="new-status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="pending">Pending</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggapan Admin</label>
                <textarea name="tanggapan" id="admin-notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Tambahkan tanggapan untuk pengadu..."></textarea>
            </div>
            <div class="flex space-x-3">
                <x-button type="button" variant="secondary" class="flex-1" onclick="closeStatusModal()">Batal</x-button>
                <x-button type="submit" variant="primary" class="flex-1">Update Status</x-button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Inline data for the detail modal
const pengaduanData = @json($pengaduans->getCollection()->keyBy('id'));

function showDetail(id) {
    const item = pengaduanData[id];
    if (!item) return;

    const statusClass = { pending: 'bg-yellow-100 text-yellow-800', proses: 'bg-blue-100 text-blue-800', selesai: 'bg-green-100 text-green-800' };
    const statusLabel = { pending: 'Pending', proses: 'Proses', selesai: 'Selesai' };

    document.getElementById('detail-content').innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700">Nama</label>
                    <p class="text-sm text-gray-900">${item.nama_pengadu}</p></div>
                <div><label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="text-sm text-gray-900">${item.email}</p></div>
                <div><label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <p class="text-sm text-gray-900">${item.telepon || '-'}</p></div>
                <div><label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <p class="text-sm text-gray-900">${item.kategori || '-'}</p></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700">Subjek</label>
                <p class="text-sm text-gray-900">${item.subjek}</p></div>
            <div><label class="block text-sm font-medium text-gray-700">Pesan</label>
                <p class="text-sm text-gray-900 whitespace-pre-wrap">${item.isi_pengaduan}</p></div>
            <div><label class="block text-sm font-medium text-gray-700">Status</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass[item.status] || 'bg-gray-100 text-gray-800'}">
                    ${statusLabel[item.status] || item.status}
                </span></div>
            ${item.tanggapan ? `<div><label class="block text-sm font-medium text-gray-700">Tanggapan Admin</label>
                <p class="text-sm text-gray-900 whitespace-pre-wrap">${item.tanggapan}</p></div>` : ''}
            <div class="text-xs text-gray-500">Dibuat: ${new Date(item.created_at).toLocaleString('id-ID')}</div>
        </div>`;
    document.getElementById('detail-modal').classList.remove('hidden');
}

function showStatusModal(id, currentStatus) {
    const form = document.getElementById('status-form');
    form.action = `/admin/pengaduan/${id}`;
    document.getElementById('new-status').value = currentStatus;
    document.getElementById('admin-notes').value = pengaduanData[id]?.tanggapan || '';
    document.getElementById('status-modal').classList.remove('hidden');
}

function closeDetailModal() { document.getElementById('detail-modal').classList.add('hidden'); }
function closeStatusModal() { document.getElementById('status-modal').classList.add('hidden'); }

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeDetailModal(); closeStatusModal(); }
});
</script>
@endpush
