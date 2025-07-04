@extends('layouts.admin')

@section('header', 'Kelola Portal Berita')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600">Dashboard</a>
    <i class="fas fa-chevron-right mx-2 text-gray-300"></i>
</li>
<li class="text-gray-600">Portal Berita</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Portal Berita Papua Tengah</h1>
            <p class="text-gray-600">Kelola berita dan informasi publik</p>
        </div>
        <a href="{{ route('admin.portal-papua-tengah.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Tambah Berita
        </a>
    </div>

    <!-- Filter dan Search -->
    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select id="kategori" name="kategori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    <option value="berita" {{ request('kategori') === 'berita' ? 'selected' : '' }}>Berita</option>
                    <option value="pengumuman" {{ request('kategori') === 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                    <option value="kegiatan" {{ request('kategori') === 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                    <option value="regulasi" {{ request('kategori') === 'regulasi' ? 'selected' : '' }}>Regulasi</option>
                    <option value="layanan" {{ request('kategori') === 'layanan' ? 'selected' : '' }}>Layanan</option>
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari berita..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="button" onclick="filterData()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </div>
    </x-card>

    <!-- Tabel Data -->
    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($portalNews as $news)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-gray-500"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $news->judul }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($news->konten, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($news->kategori === 'berita') bg-blue-100 text-blue-800
                                @elseif($news->kategori === 'pengumuman') bg-green-100 text-green-800
                                @elseif($news->kategori === 'kegiatan') bg-yellow-100 text-yellow-800
                                @elseif($news->kategori === 'regulasi') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($news->kategori) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $news->penulis }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($news->is_published)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Dipublikasikan
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $news->published_at ? $news->published_at->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-eye text-gray-400 mr-1"></i>
                                {{ $news->views }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.portal-papua-tengah.show', $news) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.portal-papua-tengah.edit', $news) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.portal-papua-tengah.destroy', $news) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <div class="py-8">
                                <i class="fas fa-inbox text-gray-300 text-4xl mb-4"></i>
                                <p class="text-lg">Tidak ada berita ditemukan</p>
                                <p class="text-sm">Mulai dengan menambahkan berita baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $portalNews->links() }}
        </div>
    </x-card>
</div>

<script>
function filterData() {
    const kategori = document.getElementById('kategori').value;
    const status = document.getElementById('status').value;
    const search = document.getElementById('search').value;
    
    const params = new URLSearchParams();
    if (kategori) params.append('kategori', kategori);
    if (status) params.append('status', status);
    if (search) params.append('search', search);
    
    window.location.href = '{{ route('admin.portal-papua-tengah.index') }}' + '?' + params.toString();
}

// Filter on Enter key
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        filterData();
    }
});
</script>
@endsection
