@extends('layouts.admin')

@section('header', 'Manajemen Pelayanan')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Pelayanan</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Pelayanan</h1>
            <p class="text-gray-600 mt-1">Kelola daftar pelayanan yang tersedia</p>
        </div>
        <a href="{{ route('admin.pelayanan.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Tambah Pelayanan
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <x-alert type="success" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error" class="mb-6">
            {{ session('error') }}
        </x-alert>
    @endif

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <form action="{{ route('admin.pelayanan.index') }}" method="GET" class="space-y-4 sm:space-y-0 sm:flex gap-4">
            <input type="text"
                   name="search"
                   placeholder="Cari pelayanan..."
                   value="{{ request('search') }}"
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

            <select name="kategori" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                @foreach(\App\Models\Pelayanan::getKategoriOptions() as $key => $label)
                    <option value="{{ $key }}" {{ request('kategori') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-search mr-2"></i>
                Cari
            </button>

            @if(request('search') || request('kategori') || request('status'))
                <a href="{{ route('admin.pelayanan.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    <i class="fas fa-redo mr-2"></i>
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">No</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama Pelayanan</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kategori</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Waktu Penyelesaian</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Biaya</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Diperbarui</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pelayanans as $pelayanan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ ($pelayanans->currentPage() - 1) * $pelayanans->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $pelayanan->nama }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($pelayanan->deskripsi, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $pelayanan->kategori_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $pelayanan->waktu_penyelesaian ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($pelayanan->biaya && is_numeric($pelayanan->biaya))
                                    Rp {{ number_format((int)$pelayanan->biaya, 0, ',', '.') }}
                                @else
                                    {{ $pelayanan->biaya ?: 'Gratis' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($pelayanan->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-times mr-1"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $pelayanan->updated_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.pelayanan.show', $pelayanan) }}"
                                       class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors"
                                       title="Lihat">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.pelayanan.edit', $pelayanan) }}"
                                       class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition-colors"
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <button type="button"
                                            onclick="deletePelayanan({{ $pelayanan->id }})"
                                            class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors"
                                            title="Hapus">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 font-medium">Tidak ada pelayanan ditemukan</p>
                                    <p class="text-gray-500 text-sm mt-1">Mulai dengan menambahkan pelayanan baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pelayanans->hasPages())
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $pelayanans->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function deletePelayanan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pelayanan ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = `{{ url('admin/pelayanan') }}/${id}`;
        form.submit();
    }
}
</script>
@endpush
@endsection
