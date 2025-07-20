@extends('layouts.public')

@section('title', 'Portal OPD - Papua Tengah')

@section('content')

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-blue-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Portal OPD Papua Tengah</h1>
                <p class="text-xl text-blue-100">Organisasi Perangkat Daerah Kabupaten Papua Tengah</p>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <form method="GET" class="max-w-md mx-auto">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari OPD..."
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <span class="bg-blue-600 text-white px-4 py-1 rounded-md text-sm hover:bg-blue-700">
                            Cari
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- OPD Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($portalOpds->count() > 0)
        <!-- Count Display -->
        <div class="mb-6 text-center">
            <p class="text-gray-600">{{ $portalOpds->total() }} OPD terdaftar</p>
        </div>
        
        <div class="opd-list grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($portalOpds as $opd)
            <div class="opd-card bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <!-- Banner -->
                <div class="h-48 bg-gradient-to-r from-blue-500 to-indigo-600 relative">
                    <img src="{{ $opd->banner_url }}" alt="{{ $opd->nama_opd }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                    
                    <!-- Logo -->
                    <div class="absolute bottom-4 left-4">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg">
                            <img src="{{ $opd->logo_url }}" alt="{{ $opd->nama_opd }}" 
                                 class="w-12 h-12 object-cover rounded-full">
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $opd->nama_opd }}</h3>
                        @if($opd->singkatan)
                        <p class="text-sm text-blue-600 font-medium">{{ $opd->singkatan }}</p>
                        @endif
                    </div>

                    @if($opd->deskripsi)
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        {{ Str::limit(strip_tags($opd->deskripsi), 120) }}
                    </p>
                    @endif

                    @if($opd->kepala_opd)
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-1">Kepala OPD</h4>
                        <p class="text-sm text-gray-900">{{ $opd->kepala_opd }}</p>
                        @if($opd->nip_kepala)
                        <p class="text-xs text-gray-500">NIP: {{ $opd->nip_kepala }}</p>
                        @endif
                    </div>
                    @endif

                    <!-- Contact Info -->
                    <div class="space-y-2 mb-4">
                        @if($opd->telepon)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-phone w-4 h-4 mr-2"></i>
                            {{ $opd->telepon }}
                        </div>
                        @endif
                        
                        @if($opd->email)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-envelope w-4 h-4 mr-2"></i>
                            {{ $opd->email }}
                        </div>
                        @endif

                        @if($opd->website)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-globe w-4 h-4 mr-2"></i>
                            <a href="{{ $opd->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                Website Resmi
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- Action Button -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('public.portal-opd.show', $opd) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-info-circle mr-2"></i>
                            Detail
                        </a>
                        
                        @if($opd->alamat)
                        <button onclick="toggleAddress({{ $opd->id }})" 
                                class="text-sm text-gray-500 hover:text-gray-700">
                            <i class="fas fa-map-marker-alt"></i>
                            Alamat
                        </button>
                        @endif
                    </div>

                    <!-- Hidden Address -->
                    @if($opd->alamat)
                    <div id="address-{{ $opd->id }}" class="hidden mt-3 p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                        <p class="text-sm text-gray-700">
                            <i class="fas fa-map-marker-alt text-yellow-600 mr-2"></i>
                            {{ $opd->formatted_alamat }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($portalOpds->hasPages())
        <div class="mt-12">
            {{ $portalOpds->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="max-w-md mx-auto">
                <div class="mb-4">
                    <i class="fas fa-building text-gray-300 text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada OPD ditemukan</h3>
                <p class="text-gray-500">
                    @if(request('search'))
                        Tidak ada OPD yang sesuai dengan pencarian "{{ request('search') }}".
                    @else
                        Belum ada data OPD yang tersedia.
                    @endif
                </p>
                @if(request('search'))
                <a href="{{ route('public.portal-opd.index') }}" 
                   class="inline-flex items-center px-4 py-2 mt-4 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    Lihat Semua OPD
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function toggleAddress(opdId) {
    const addressElement = document.getElementById('address-' + opdId);
    if (addressElement.classList.contains('hidden')) {
        addressElement.classList.remove('hidden');
    } else {
        addressElement.classList.add('hidden');
    }
}
</script>

@endsection
