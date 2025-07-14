@extends('layouts.admin')

@section('main-content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen FAQ</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-home mr-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-chevron-right mx-2 text-gray-300"></i>
                            <span class="text-gray-600">FAQ</span>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex items-center space-x-3">
                <x-button 
                    href="{{ route('admin.faq.create') }}"
                    variant="primary" 
                    size="md"
                >
                    <i class="fas fa-plus mr-2"></i>Tambah FAQ
                </x-button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-question-circle text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total FAQ</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $faqs->total() ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Aktif</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $faqs->where('status', true)->count() ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-star text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Featured</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $faqs->where('is_featured', true)->count() ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-eye text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Views</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $faqs->sum('view_count') ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.faq.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <x-search-input 
                            name="search"
                            placeholder="Cari FAQ..."
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
                            <option value="umum" {{ request('kategori') == 'umum' ? 'selected' : '' }}>Umum</option>
                            <option value="layanan" {{ request('kategori') == 'layanan' ? 'selected' : '' }}>Layanan</option>
                            <option value="pengaduan" {{ request('kategori') == 'pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                            <option value="audit" {{ request('kategori') == 'audit' ? 'selected' : '' }}>Audit</option>
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
                        onclick="window.location.href='{{ route('admin.faq.index') }}'"
                    >
                        <i class="fas fa-undo mr-2"></i>Reset
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- FAQ List -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Update</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($faqs as $index => $faq)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $faqs->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($faq->pertanyaan, 60) }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit(strip_tags($faq->jawaban), 80) }}</div>
                                @if($faq->is_featured)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @switch($faq->kategori)
                                        @case('umum') bg-gray-100 text-gray-800 @break
                                        @case('layanan') bg-blue-100 text-blue-800 @break
                                        @case('pengaduan') bg-yellow-100 text-yellow-800 @break
                                        @case('audit') bg-green-100 text-green-800 @break
                                        @default bg-purple-100 text-purple-800
                                    @endswitch
                                ">
                                    {{ ucfirst($faq->kategori) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.faq.toggle-status', $faq->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full transition-colors
                                        {{ $faq->status ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        <i class="fas {{ $faq->status ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $faq->status ? 'Aktif' : 'Non-aktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-900">{{ $faq->urutan ?? '-' }}</span>
                                    <div class="flex space-x-1">
                                        <form method="POST" action="{{ route('admin.faq.move-up', $faq->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-1 text-gray-400 hover:text-gray-600" title="Naik">
                                                <i class="fas fa-arrow-up text-xs"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.faq.move-down', $faq->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-1 text-gray-400 hover:text-gray-600" title="Turun">
                                                <i class="fas fa-arrow-down text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $faq->view_count ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $faq->updated_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.faq.show', $faq->id) }}" 
                                       class="text-blue-600 hover:text-blue-900" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.faq.edit', $faq->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="deleteFaq({{ $faq->id }})" 
                                            class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-question-circle text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Tidak ada FAQ ditemukan</p>
                                    <p class="text-sm">
                                        @if(request('search') || request('kategori') || request('status'))
                                            Coba ubah filter pencarian atau 
                                            <a href="{{ route('admin.faq.index') }}" class="text-blue-600 hover:text-blue-800">reset filter</a>
                                        @else
                                            <a href="{{ route('admin.faq.create') }}" class="text-blue-600 hover:text-blue-800">Tambah FAQ pertama</a>
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    {{-- Add pagination here when connected to real data --}}
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteFaq(id) {
    if (confirm('Apakah Anda yakin ingin menghapus FAQ ini?')) {
        const form = document.getElementById('delete-form');
        form.action = `/admin/faq/${id}`;
        form.submit();
    }
}
</script>
@endsection