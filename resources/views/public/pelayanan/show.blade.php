@extends('layouts.public')

@section('title', $pelayanan->nama_layanan . ' - Layanan Publik - Inspektorat Papua Tengah')
@section('description', $pelayanan->deskripsi)

@section('content')

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center mb-6">
                <a href="{{ route('public.pelayanan.index') }}" 
                   class="inline-flex items-center text-blue-100 hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Kembali ke Layanan</span>
                </a>
            </div>
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    {{ $pelayanan->nama_layanan }}
                </h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    {{ $pelayanan->deskripsi }}
                </p>
            </div>
        </div>
    </section>

    <!-- Service Details Section -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Service Overview -->
                <div class="p-8 border-b border-gray-200">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            @if($pelayanan->kategori === 'konsultasi')
                                <i class="fas fa-comments text-blue-600 text-3xl"></i>
                            @elseif($pelayanan->kategori === 'audit')
                                <i class="fas fa-search text-blue-600 text-3xl"></i>
                            @elseif($pelayanan->kategori === 'reviu')
                                <i class="fas fa-file-alt text-blue-600 text-3xl"></i>
                            @elseif($pelayanan->kategori === 'evaluasi')
                                <i class="fas fa-chart-line text-blue-600 text-3xl"></i>
                            @else
                                <i class="fas fa-concierge-bell text-blue-600 text-3xl"></i>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($pelayanan->kategori) }}
                                </span>
                                @if($pelayanan->status === 'aktif')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Aktif
                                </span>
                                @endif
                            </div>
                            
                            <h2 class="text-2xl font-bold text-gray-900 mb-3">
                                {{ $pelayanan->nama_layanan }}
                            </h2>
                            
                            <p class="text-gray-600 leading-relaxed">
                                {{ $pelayanan->deskripsi }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Service Information Grid -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Layanan</h3>
                            <dl class="space-y-4">
                                @if($pelayanan->persyaratan)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Persyaratan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $pelayanan->persyaratan }}</dd>
                                </div>
                                @endif
                                
                                @if($pelayanan->waktu_pelayanan)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Waktu Pelayanan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <i class="fas fa-clock text-blue-600 mr-2"></i>
                                        {{ $pelayanan->waktu_pelayanan }}
                                    </dd>
                                </div>
                                @endif
                                
                                @if($pelayanan->biaya)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Biaya</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <i class="fas fa-tag text-green-600 mr-2"></i>
                                        @if($pelayanan->biaya === 'Gratis' || strtolower($pelayanan->biaya) === 'gratis')
                                            <span class="text-green-600 font-medium">Gratis</span>
                                        @elseif(is_numeric($pelayanan->biaya))
                                            <span class="text-green-600 font-medium">Rp {{ number_format($pelayanan->biaya) }}</span>
                                        @else
                                            <span class="text-green-600 font-medium">{{ $pelayanan->biaya }}</span>
                                        @endif
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Process Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Prosedur Layanan</h3>
                            @if($pelayanan->prosedur)
                            <div class="prose prose-sm max-w-none">
                                {!! nl2br(e($pelayanan->prosedur)) !!}
                            </div>
                            @else
                            <p class="text-gray-600">Prosedur layanan akan diinformasikan lebih lanjut.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="bg-gray-50 p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Kontak & Informasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Phone -->
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-phone text-blue-600"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-1">Telepon</h4>
                            <p class="text-sm text-gray-600">(0984) 21234</p>
                        </div>

                        <!-- Email -->
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-1">Email</h4>
                            <p class="text-sm text-gray-600">inspektorat@papuatengah.go.id</p>
                        </div>

                        <!-- Office Hours -->
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-clock text-blue-600"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-1">Jam Layanan</h4>
                            <p class="text-sm text-gray-600">{{ config('contact.jam_operasional') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-8 bg-white border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('public.wbs') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-comments mr-2"></i>
                            Ajukan Keluhan/Saran
                        </a>
                        
                        <a href="{{ route('public.kontak') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-phone mr-2"></i>
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Services Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Layanan Terkait
                </h2>
                <p class="text-xl text-gray-600">
                    Layanan lain yang mungkin Anda butuhkan
                </p>
            </div>

            @if($relatedServices && $relatedServices->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($relatedServices as $related)
                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        @if($related->kategori === 'konsultasi')
                            <i class="fas fa-comments text-blue-600"></i>
                        @elseif($related->kategori === 'audit')
                            <i class="fas fa-search text-blue-600"></i>
                        @elseif($related->kategori === 'reviu')
                            <i class="fas fa-file-alt text-blue-600"></i>
                        @elseif($related->kategori === 'evaluasi')
                            <i class="fas fa-chart-line text-blue-600"></i>
                        @else
                            <i class="fas fa-concierge-bell text-blue-600"></i>
                        @endif
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        {{ $related->nama_layanan }}
                    </h3>
                    
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        {{ $related->deskripsi }}
                    </p>
                    
                    <a href="{{ route('public.pelayanan.show', $related->id) }}" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-gray-600">Belum ada layanan terkait.</p>
            </div>
            @endif
        </div>
    </section>
</div>
@endsection
