@extends('layouts.admin')

@section('title', 'Detail Hero Slider')

@section('header', 'Detail Hero Slider')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.hero-sliders.index') }}" class="text-blue-600 hover:text-blue-800">Hero Slider</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Detail</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">{{ $heroSlider->judul }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.hero-sliders.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <a href="{{ route('admin.hero-sliders.edit', $heroSlider) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                @if($heroSlider->gambar)
                <img src="{{ asset('storage/' . $heroSlider->gambar) }}" alt="{{ $heroSlider->judul }}" class="w-full aspect-video object-cover">
                @else
                <div class="w-full aspect-video bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-6xl text-gray-400"></i>
                </div>
                @endif
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $heroSlider->judul }}</h2>
                    @if($heroSlider->subjudul)
                    <p class="text-lg text-gray-600 mb-4">{{ $heroSlider->subjudul }}</p>
                    @endif
                    @if($heroSlider->deskripsi)
                    <p class="text-gray-700">{{ $heroSlider->deskripsi }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs rounded-full {{ $heroSlider->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($heroSlider->status) }}
                            </span>
                            @if($heroSlider->is_active)
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 ml-1">Aktif</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Prioritas</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($heroSlider->prioritas ?? 'Sedang') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Urutan</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $heroSlider->urutan }}</dd>
                    </div>
                    @if($heroSlider->link_url)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Link</dt>
                        <dd class="mt-1 text-sm text-blue-600">
                            <a href="{{ $heroSlider->link_url }}" target="_blank">{{ $heroSlider->link_text ?? $heroSlider->link_url }}</a>
                        </dd>
                    </div>
                    @endif
                    @if($heroSlider->tanggal_mulai || $heroSlider->tanggal_selesai)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Periode</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $heroSlider->tanggal_mulai?->format('d M Y') ?? '-' }} s/d {{ $heroSlider->tanggal_selesai?->format('d M Y') ?? '-' }}
                        </dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Views</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($heroSlider->views ?? 0) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $heroSlider->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Diperbarui</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $heroSlider->updated_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
