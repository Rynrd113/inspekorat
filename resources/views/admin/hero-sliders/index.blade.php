@extends('layouts.admin')

@section('title', 'Manajemen Foto Slider')

@section('header', 'Manajemen Foto Slider')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Foto Slider</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Foto Slider</h1>
            <p class="text-gray-600 mt-1">Kelola foto slider di halaman utama website</p>
        </div>
        <a href="{{ route('admin.hero-sliders.create') }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah Foto
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                       placeholder="Cari judul...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Aktif</label>
                <select name="is_active" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="true" {{ request('is_active') == 'true' ? 'selected' : '' }}>Aktif</option>
                    <option value="false" {{ request('is_active') == 'false' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                    <i class="fas fa-search mr-1"></i>Filter
                </button>
                <a href="{{ route('admin.hero-sliders.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Sliders Grid -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-images mr-2 text-blue-600"></i>
                Daftar Foto ({{ $heroSliders->total() }})
            </h2>
        </div>

        @if($heroSliders->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
            @foreach($heroSliders as $slider)
            <div class="group relative bg-gray-100 rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
                <!-- Image -->
                <div class="aspect-video relative">
                    @if($slider->gambar)
                        <img src="{{ $slider->gambar_url }}" alt="{{ $slider->judul ?? 'Slider' }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-white/50"></i>
                        </div>
                    @endif

                    <!-- Status Badges -->
                    <div class="absolute top-2 left-2 flex gap-1">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $slider->status === 'published' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white' }}">
                            {{ ucfirst($slider->status) }}
                        </span>
                        @if($slider->is_active)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-500 text-white">
                            Aktif
                        </span>
                        @endif
                    </div>

                    <!-- Order Badge -->
                    <div class="absolute top-2 right-2">
                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-black/50 text-white">
                            #{{ $slider->urutan }}
                        </span>
                    </div>

                    <!-- Hover Actions -->
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                        <a href="{{ route('admin.hero-sliders.edit', $slider) }}" 
                           class="px-4 py-2 bg-white text-gray-800 rounded-md hover:bg-gray-100 text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.hero-sliders.destroy', $slider) }}" method="POST" class="inline" 
                              onsubmit="return confirm('Yakin hapus foto slider ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm font-medium">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Info -->
                @if($slider->judul)
                <div class="p-3">
                    <h3 class="font-medium text-gray-900 truncate">{{ $slider->judul }}</h3>
                    @if($slider->subjudul)
                    <p class="text-sm text-gray-500 truncate">{{ $slider->subjudul }}</p>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
        
        <div class="p-4 border-t border-gray-200">
            {{ $heroSliders->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <i class="fas fa-images text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada foto slider</h3>
            <p class="text-gray-500 mb-4">Upload foto pertama untuk ditampilkan di halaman utama</p>
            <a href="{{ route('admin.hero-sliders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Tambah Foto
            </a>
        </div>
        @endif
    </div>

    <!-- Tips -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h4 class="font-medium text-blue-900 mb-2"><i class="fas fa-lightbulb mr-2"></i>Tips</h4>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Foto dengan urutan lebih kecil akan tampil lebih dulu</li>
            <li>• Pastikan status "Published" dan "Aktif" agar foto tampil di website</li>
            <li>• Ukuran foto ideal: 1920 x 800 pixel (landscape)</li>
            <li>• Gunakan foto berkualitas tinggi untuk hasil terbaik</li>
        </ul>
    </div>
</div>
@endsection
