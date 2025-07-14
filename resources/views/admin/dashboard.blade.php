@extends('layouts.admin')

@section('header', 'Dashboard')
@section('breadcrumb')
<li><span class="text-gray-500">Dashboard</span></li>
@endsection

@section('main-content')
<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @if(auth()->user()->hasAnyRole(['admin_wbs', 'wbs_manager', 'admin', 'superadmin']))
    <x-card class="hover:shadow-lg transition-shadow duration-200">
        <div class="text-center">
            <div class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Kelola WBS</h3>
            <p class="text-gray-600 text-sm mb-4">Lihat, respons, dan kelola laporan WBS</p>
            <x-button 
                href="{{ route('admin.wbs.index') }}"
                variant="primary" 
                size="md"
            >
                <i class="fas fa-arrow-right mr-2"></i>Kelola WBS
            </x-button>
        </div>
    </x-card>
    @endif

    @if(auth()->user()->hasAnyRole(['admin_berita', 'content_manager', 'admin', 'superadmin']))
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
    @endif

    @if(auth()->user()->hasAnyRole(['admin_portal_opd', 'opd_manager', 'admin', 'superadmin']))
    <x-card class="hover:shadow-lg transition-shadow duration-200">
        <div class="text-center">
            <div class="bg-emerald-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-building text-emerald-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Portal OPD</h3>
            <p class="text-gray-600 text-sm mb-4">Kelola informasi dan profil OPD</p>
            <a href="{{ route('admin.portal-opd.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
                <i class="fas fa-arrow-right mr-2"></i>Kelola Portal OPD
            </a>
        </div>
    </x-card>
    @endif

    @if(auth()->user()->hasAnyRole(['admin_faq', 'content_manager', 'admin', 'superadmin']))
    <x-card class="hover:shadow-lg transition-shadow duration-200">
        <div class="text-center">
            <div class="bg-teal-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-question-circle text-teal-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">FAQ</h3>
            <p class="text-gray-600 text-sm mb-4">Kelola pertanyaan yang sering diajukan</p>
            <a href="{{ route('admin.faq.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-md hover:bg-teal-700 transition-colors">
                <i class="fas fa-arrow-right mr-2"></i>Kelola FAQ
            </a>
        </div>
    </x-card>
    @endif

    @if(auth()->user()->hasAnyRole(['admin_pelayanan', 'service_manager', 'admin', 'superadmin']))
    <x-card class="hover:shadow-lg transition-shadow duration-200">
        <div class="text-center">
            <div class="bg-cyan-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-concierge-bell text-cyan-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Pelayanan</h3>
            <p class="text-gray-600 text-sm mb-4">Kelola layanan publik dan prosedur</p>
            <a href="{{ route('admin.pelayanan.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white text-sm font-medium rounded-md hover:bg-cyan-700 transition-colors">
                <i class="fas fa-arrow-right mr-2"></i>Kelola Pelayanan
            </a>
        </div>
    </x-card>
    @endif

    @if(auth()->user()->hasAnyRole(['admin_dokumen', 'service_manager', 'admin', 'superadmin']))
    <x-card class="hover:shadow-lg transition-shadow duration-200">
        <div class="text-center">
            <div class="bg-amber-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-file-alt text-amber-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Dokumen</h3>
            <p class="text-gray-600 text-sm mb-4">Kelola repository dokumen publik</p>
            <a href="{{ route('admin.dokumen.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-md hover:bg-amber-700 transition-colors">
                <i class="fas fa-arrow-right mr-2"></i>Kelola Dokumen
            </a>
        </div>
    </x-card>
    @endif

    @if(auth()->user()->hasAnyRole(['admin_galeri', 'content_manager', 'admin', 'superadmin']))
    <x-card class="hover:shadow-lg transition-shadow duration-200">
        <div class="text-center">
            <div class="bg-pink-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-images text-pink-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Galeri</h3>
            <p class="text-gray-600 text-sm mb-4">Kelola galeri foto dan video</p>
            <a href="{{ route('admin.galeri.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-pink-600 text-white text-sm font-medium rounded-md hover:bg-pink-700 transition-colors">
                <i class="fas fa-arrow-right mr-2"></i>Kelola Galeri
            </a>
        </div>
    </x-card>
    @endif

    @if(auth()->user()->canApproveContent())
    <x-card class="hover:shadow-lg transition-shadow duration-200">
        <div class="text-center">
            <div class="bg-orange-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-check-circle text-orange-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Persetujuan</h3>
            <p class="text-gray-600 text-sm mb-4">Kelola persetujuan konten</p>
            <a href="{{ route('admin.approvals.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-md hover:bg-orange-700 transition-colors">
                <i class="fas fa-arrow-right mr-2"></i>Kelola Persetujuan
            </a>
        </div>
    </x-card>
    @endif

    @if(auth()->user()->isSuperAdmin())
    <x-card class="hover:shadow-lg transition-shadow duration-200">
        <div class="text-center">
            <div class="bg-indigo-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-users text-indigo-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Manajemen User</h3>
            <p class="text-gray-600 text-sm mb-4">Kelola pengguna dan hak akses sistem</p>
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors">
                <i class="fas fa-arrow-right mr-2"></i>Kelola User
            </a>
        </div>
    </x-card>
    @endif

    @if(auth()->user()->isSuperAdmin())
    <x-card class="hover:shadow-lg transition-shadow duration-200">
        <div class="text-center">
            <div class="bg-slate-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-cogs text-slate-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfigurasi</h3>
            <p class="text-gray-600 text-sm mb-4">Kelola pengaturan sistem</p>
            <a href="{{ route('admin.configurations.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-md hover:bg-slate-700 transition-colors">
                <i class="fas fa-arrow-right mr-2"></i>Kelola Konfigurasi
            </a>
        </div>
    </x-card>
    @endif

    @if(auth()->user()->isSuperAdmin())
    <x-card class="hover:shadow-lg transition-shadow duration-200">
        <div class="text-center">
            <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-history text-gray-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Audit Log</h3>
            <p class="text-gray-600 text-sm mb-4">Lihat log aktivitas sistem</p>
            <a href="{{ route('admin.audit-logs.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-right mr-2"></i>Lihat Log
            </a>
        </div>
    </x-card>
    @endif
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
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
                <div class="text-yellow-100">WBS Pending</div>
            </div>
        </div>
    </x-card>

    <!-- Portal Papua Tengah -->
    <x-card class="bg-gradient-to-r from-purple-500 to-pink-500 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-newspaper text-2xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <div class="text-3xl font-bold">{{ $stats['portal_papua_tengah']['active'] }}</div>
                <div class="text-purple-100">Berita Aktif</div>
            </div>
        </div>
    </x-card>

    <!-- Portal OPD -->
    <x-card class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-building text-2xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <div class="text-3xl font-bold">{{ $stats['portal_opd']['active'] ?? 0 }}</div>
                <div class="text-emerald-100">OPD Aktif</div>
            </div>
        </div>
    </x-card>

    <!-- Total Users -->
    <x-card class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <div class="text-3xl font-bold">{{ $stats['users']['total'] ?? 0 }}</div>
                <div class="text-indigo-100">Total User</div>
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
