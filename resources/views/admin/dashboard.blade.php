@extends('layouts.admin')

@section('header', 'Dashboard')
@section('breadcrumb')
<li><span class="text-gray-500">Dashboard</span></li>
@endsection

@section('main-content')
<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <x-card class="hover:shadow-lg transition-shadow duration-200">
        <div class="text-center">
            <div class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Kelola WBS</h3>
            <p class="text-gray-600 text-sm mb-4">Lihat, respons, dan kelola laporan WBS</p>
            <a href="{{ route('admin.wbs.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-arrow-right mr-2"></i>Kelola WBS
            </a>
        </div>
    </x-card>

    <x-card class="hover:shadow-lg transition-shadow duration-200">
        <div class="text-center">
            <div class="bg-purple-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-newspaper text-purple-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Portal Papua Tengah</h3>
            <p class="text-gray-600 text-sm mb-4">Kelola konten berita dan informasi portal</p>
            <a href="{{ route('admin.portal-papua-tengah.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition-colors">
                <i class="fas fa-arrow-right mr-2"></i>Kelola Portal Papua Tengah
            </a>
        </div>
    </x-card>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total WBS -->
    <x-card class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <div class="text-3xl font-bold">{{ $stats['wbs']['total'] }}</div>
                <div class="text-blue-100">Total WBS</div>
            </div>
        </div>
    </x-card>

    <!-- WBS Pending -->
    <x-card class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <div class="text-3xl font-bold">{{ $stats['wbs']['pending'] }}</div>
                <div class="text-yellow-100">Pending</div>
            </div>
        </div>
    </x-card>

    <!-- Portal Papua Tengah -->
    <x-card class="bg-gradient-to-r from-green-500 to-teal-500 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-newspaper text-2xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <div class="text-3xl font-bold">{{ $stats['portal_papua_tengah']['active'] }}/{{ $stats['portal_papua_tengah']['total'] }}</div>
                <div class="text-green-100">Portal Papua Tengah Aktif</div>
            </div>
        </div>
    </x-card>

    <!-- Info Kantor -->
    <x-card class="bg-gradient-to-r from-purple-500 to-pink-500 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-building text-2xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <div class="text-3xl font-bold">{{ $stats['info_kantor']['active'] }}/{{ $stats['info_kantor']['total'] }}</div>
                <div class="text-purple-100">Info Kantor Aktif</div>
            </div>
        </div>
    </x-card>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- WBS Status Chart -->
    <x-card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900">Status WBS</h3>
        </x-slot:header>

        <div class="space-y-4">
            <!-- Pending -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">Pending</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 mr-2">{{ $stats['wbs']['pending'] }}</span>
                    <div class="w-24 bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $stats['wbs']['total'] > 0 ? ($stats['wbs']['pending'] / $stats['wbs']['total']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">In Progress</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 mr-2">{{ $stats['wbs']['in_progress'] }}</span>
                    <div class="w-24 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $stats['wbs']['total'] > 0 ? ($stats['wbs']['in_progress'] / $stats['wbs']['total']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Resolved -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">Resolved</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 mr-2">{{ $stats['wbs']['resolved'] }}</span>
                    <div class="w-24 bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stats['wbs']['total'] > 0 ? ($stats['wbs']['resolved'] / $stats['wbs']['total']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Rejected -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">Rejected</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 mr-2">{{ $stats['wbs']['rejected'] }}</span>
                    <div class="w-24 bg-gray-200 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $stats['wbs']['total'] > 0 ? ($stats['wbs']['rejected'] / $stats['wbs']['total']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        @if($stats['wbs']['total'] == 0)
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500">Belum ada laporan WBS</p>
            </div>
        @endif
    </x-card>

    <!-- Recent WBS -->
    <x-card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Laporan WBS Terbaru</h3>
                <a href="{{ route('admin.wbs.index') }}" class="text-sm text-green-600 hover:text-green-700">
                    Lihat Semua
                </a>
            </div>
        </x-slot:header>

        <div class="space-y-4">
            @forelse($recentWbs as $wbs)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $wbs->nama_pelapor }}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                            {{ $wbs->subjek }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $wbs->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="ml-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($wbs->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($wbs->status == 'in_progress') bg-blue-100 text-blue-800
                            @elseif($wbs->status == 'resolved') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $wbs->status)) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">Belum ada laporan WBS</p>
                </div>
            @endforelse
        </div>
    </x-card>
</div>

@endsection

@push('scripts')
<script>
// Auto refresh stats every 5 minutes
setTimeout(() => {
    window.location.reload();
}, 300000);

// Real-time clock
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID');
    const dateString = now.toLocaleDateString('id-ID', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    // You can add a clock element in the header if needed
    console.log(`${dateString} - ${timeString}`);
}

setInterval(updateClock, 1000);
updateClock();
</script>
@endpush
