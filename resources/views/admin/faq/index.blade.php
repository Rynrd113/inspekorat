@extends('layouts.admin')

@section('header', 'Manajemen FAQ')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">FAQ</li>
@endsection

@section('main-content')
<!-- Success/Error Notifications -->
@if(session('success'))
    <x-alert variant="success" dismissible="true">
        {{ session('success') }}
    </x-alert>
@endif

@if(session('error'))
    <x-alert variant="error" dismissible="true">
        {{ session('error') }}
    </x-alert>
@endif

@if($errors->any())
    <x-alert variant="error" dismissible="true" title="Terjadi kesalahan">
        <ul class="mt-2 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif

<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar FAQ</h1>
            <p class="text-gray-600 mt-1">Kelola pertanyaan yang sering diajukan</p>
        </div>
        <x-button 
            href="{{ route('admin.faq.create') }}"
            variant="primary" 
            size="md"
        >
            <i class="fas fa-plus mr-2"></i>Tambah FAQ
        </x-button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-question-circle text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $faqs->total() ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total FAQ</div>
                </div>
            </div>
        </x-card>

        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $faqs->where('status', true)->count() ?? 0 }}</div>
                    <div class="text-sm text-gray-500">FAQ Aktif</div>
                </div>
            </div>
        </x-card>

        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $faqs->where('is_featured', true)->count() ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Featured</div>
                </div>
            </div>
        </x-card>

        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-eye text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $faqs->sum('view_count') ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total Views</div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Search and Filter -->
    <x-card>
        <x-slot:header>
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-filter mr-2 text-blue-600"></i>Filter & Pencarian
            </h2>
        </x-slot:header>
        
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
                    <select name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
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
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Non-aktif</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-gray-200">
                <x-button type="submit" variant="primary" size="md" id="search-btn">
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
    </x-card>

    <!-- FAQ List -->
    <x-card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-question-circle mr-2 text-blue-600"></i>Daftar FAQ
                </h2>
                <div class="text-sm text-gray-500">
                    Total: {{ $faqs->total() ?? 0 }} FAQ
                </div>
            </div>
        </x-slot:header>
        
        <!-- Mobile Card View (hidden on desktop) -->
        <div class="block md:hidden space-y-4">
            @forelse($faqs as $index => $faq)
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 mb-1">{{ Str::limit($faq->pertanyaan, 50) }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ Str::limit(strip_tags($faq->jawaban), 60) }}</p>
                            
                            <div class="flex flex-wrap gap-2 items-center">
                                <x-badge variant="@switch($faq->kategori)
                                    @case('umum') secondary @break
                                    @case('layanan') primary @break
                                    @case('pengaduan') warning @break
                                    @case('audit') success @break
                                    @default info
                                @endswitch" size="sm">
                                    {{ ucfirst($faq->kategori) }}
                                </x-badge>
                                
                                @if($faq->is_featured)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>Featured
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2 ml-4">
                            <a href="{{ route('admin.faq.show', $faq->id) }}" class="text-blue-600 hover:text-blue-900 p-1" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.faq.edit', $faq->id) }}" class="text-indigo-600 hover:text-indigo-900 p-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" onclick="deleteFaq({{ $faq->id }})" class="text-red-600 hover:text-red-900 p-1" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 pt-2 border-t border-gray-100">
                        <div class="flex items-center space-x-4">
                            <span><i class="fas fa-eye mr-1"></i>{{ $faq->view_count ?? 0 }}</span>
                            <span><i class="fas fa-sort mr-1"></i>{{ $faq->urutan ?? '-' }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <form method="POST" action="{{ route('admin.faq.toggle-status', $faq->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full transition-colors
                                    {{ $faq->status ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                    <i class="fas {{ $faq->status ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                    {{ $faq->status ? 'Aktif' : 'Non-aktif' }}
                                </button>
                            </form>
                            <span class="text-xs">{{ $faq->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                @if(request('search') || request('kategori') || request('status'))
                    <x-empty-state
                        title="Tidak ada FAQ yang sesuai filter"
                        description="Tidak ditemukan FAQ yang cocok dengan pencarian atau filter yang Anda gunakan."
                        icon="fas fa-search"
                        :action="true"
                        actionText="Reset Filter"
                        actionUrl="{{ route('admin.faq.index') }}"
                        actionVariant="secondary"
                        suggestion="Coba gunakan kata kunci yang berbeda atau pilih kategori lain."
                    />
                @else
                    <x-empty-state
                        title="Belum ada FAQ tersedia"
                        description="Mulai dengan menambahkan FAQ pertama untuk membantu pengunjung website."
                        icon="fas fa-question-circle"
                        :action="true"
                        actionText="Tambah FAQ"
                        actionUrl="{{ route('admin.faq.create') }}"
                        suggestion="FAQ yang baik akan membantu mengurangi pertanyaan yang masuk ke customer service."
                    />
                @endif
            @endforelse
        </div>
        
        <!-- Desktop Table View (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
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
                                <x-badge 
                                    variant="@switch($faq->kategori)
                                        @case('umum') secondary @break
                                        @case('layanan') primary @break
                                        @case('pengaduan') warning @break
                                        @case('audit') success @break
                                        @default info
                                    @endswitch"
                                    size="md"
                                >
                                    {{ ucfirst($faq->kategori) }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.faq.toggle-status', $faq->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full transition-colors
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
                                <div class="flex space-x-2">
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
                            <td colspan="8" class="px-6 py-4">
                                @if(request('search') || request('kategori') || request('status'))
                                    <x-empty-state
                                        title="Tidak ada FAQ yang sesuai filter"
                                        description="Tidak ditemukan FAQ yang cocok dengan pencarian atau filter yang Anda gunakan."
                                        icon="fas fa-search"
                                        :action="true"
                                        actionText="Reset Filter"
                                        actionUrl="{{ route('admin.faq.index') }}"
                                        actionVariant="secondary"
                                        suggestion="Coba gunakan kata kunci yang berbeda atau pilih kategori lain."
                                    />
                                @else
                                    <x-empty-state
                                        title="Belum ada FAQ tersedia"
                                        description="Mulai dengan menambahkan FAQ pertama untuk membantu pengunjung website."
                                        icon="fas fa-question-circle"
                                        :action="true"
                                        actionText="Tambah FAQ"
                                        actionUrl="{{ route('admin.faq.create') }}"
                                        suggestion="FAQ yang baik akan membantu mengurangi pertanyaan yang masuk ke customer service."
                                    />
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-card>

    <!-- Pagination -->
    @if($faqs->hasPages())
    <div class="flex justify-center">
        {{ $faqs->appends(request()->query())->links() }}
    </div>
    @endif
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
        
        // Show loading state
        const deleteButtons = document.querySelectorAll('[onclick*="deleteFaq"]');
        deleteButtons.forEach(btn => {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menghapus...';
            btn.disabled = true;
        });
        
        form.submit();
    }
}

// Add loading state to search form
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('form[method="GET"]');
    const searchBtn = document.getElementById('search-btn');
    
    if (searchForm && searchBtn) {
        searchForm.addEventListener('submit', function() {
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...';
            searchBtn.disabled = true;
        });
    }
    
    // Add loading state to status toggle buttons
    const statusForms = document.querySelectorAll('form[action*="toggle-status"]');
    statusForms.forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...';
            btn.disabled = true;
        });
    });
});
</script>
@endsection