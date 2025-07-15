@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('header', 'Dashboard')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}</h1>
            <p class="text-gray-600 mt-1">Kelola konten dan sistem Portal Inspektorat Papua Tengah</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="text-sm text-gray-600">
                <i class="fas fa-clock mr-1"></i>
                {{ now()->format('d M Y, H:i') }}
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @if(auth()->user()->hasAnyRole(['admin_wbs', 'wbs_manager', 'admin', 'superadmin']))
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-shield-alt text-2xl text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Laporan WBS</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Wbs::count() }}</p>
                    <p class="text-xs text-gray-500">
                        <span class="text-yellow-600">{{ \App\Models\Wbs::where('status', 'pending')->count() }} pending</span>
                    </p>
                </div>
            </div>
        </x-card>
        @endif

        @if(auth()->user()->hasAnyRole(['admin_berita', 'content_manager', 'admin', 'superadmin']))
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-newspaper text-2xl text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Berita</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\PortalPapuaTengah::count() }}</p>
                    <p class="text-xs text-gray-500">
                        <span class="text-green-600">{{ \App\Models\PortalPapuaTengah::where('is_published', true)->count() }} published</span>
                    </p>
                </div>
            </div>
        </x-card>
        @endif

        @if(auth()->user()->hasAnyRole(['admin_portal_opd', 'opd_manager', 'admin', 'superadmin']))
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-building text-2xl text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Portal OPD</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\PortalOpd::count() }}</p>
                    <p class="text-xs text-gray-500">Organisasi Perangkat Daerah</p>
                </div>
            </div>
        </x-card>
        @endif

        @if(auth()->user()->isSuperAdmin())
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-2xl text-gray-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                    <p class="text-xs text-gray-500">
                        <span class="text-blue-600">{{ \App\Models\User::where('created_at', '>=', now()->subDays(30))->count() }} bulan ini</span>
                    </p>
                </div>
            </div>
        </x-card>
        @endif
    </div>

    <!-- Quick Actions -->
    <x-card>
        <x-slot:header>
            <h3 class="text-lg font-medium text-gray-900">Aksi Cepat</h3>
        </x-slot:header>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @if(auth()->user()->hasAnyRole(['admin_wbs', 'wbs_manager', 'admin', 'superadmin']))
            <div class="text-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="bg-blue-100 rounded-full p-3 w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-blue-600 text-lg"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Kelola WBS</h4>
                <p class="text-xs text-gray-600 mb-3">Lihat dan kelola laporan</p>
                <a href="{{ route('admin.wbs.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Kelola
                </a>
            </div>
            @endif

            @if(auth()->user()->hasAnyRole(['admin_berita', 'content_manager', 'admin', 'superadmin']))
            <div class="text-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <div class="bg-purple-100 rounded-full p-3 w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-newspaper text-purple-600 text-lg"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Portal Papua Tengah</h4>
                <p class="text-xs text-gray-600 mb-3">Kelola berita dan konten</p>
                <a href="{{ route('admin.portal-papua-tengah.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-md transition-colors">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Kelola
                </a>
            </div>
            @endif

            @if(auth()->user()->hasAnyRole(['admin_portal_opd', 'opd_manager', 'admin', 'superadmin']))
            <div class="text-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="bg-green-100 rounded-full p-3 w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-building text-green-600 text-lg"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Portal OPD</h4>
                <p class="text-xs text-gray-600 mb-3">Kelola data organisasi</p>
                <a href="{{ route('admin.portal-opd.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Kelola
                </a>
            </div>
            @endif

            @if(auth()->user()->hasAnyRole(['admin_faq', 'content_manager', 'admin', 'superadmin']))
            <div class="text-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <div class="bg-yellow-100 rounded-full p-3 w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-question-circle text-yellow-600 text-lg"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">FAQ</h4>
                <p class="text-xs text-gray-600 mb-3">Kelola pertanyaan umum</p>
                <a href="{{ route('admin.faq.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-medium rounded-md transition-colors">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Kelola
                </a>
            </div>
            @endif

            @if(auth()->user()->hasAnyRole(['admin_pelayanan', 'service_manager', 'admin', 'superadmin']))
            <div class="text-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                <div class="bg-indigo-100 rounded-full p-3 w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-concierge-bell text-indigo-600 text-lg"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Pelayanan</h4>
                <p class="text-xs text-gray-600 mb-3">Kelola layanan publik</p>
                <a href="{{ route('admin.pelayanan.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition-colors">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Kelola
                </a>
            </div>
            @endif

            @if(auth()->user()->hasAnyRole(['admin_dokumen', 'service_manager', 'admin', 'superadmin']))
            <div class="text-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <div class="bg-orange-100 rounded-full p-3 w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-file-alt text-orange-600 text-lg"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Dokumen</h4>
                <p class="text-xs text-gray-600 mb-3">Kelola dokumen publik</p>
                <a href="{{ route('admin.dokumen.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded-md transition-colors">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Kelola
                </a>
            </div>
            @endif

            @if(auth()->user()->hasAnyRole(['admin_galeri', 'content_manager', 'admin', 'superadmin']))
            <div class="text-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition-colors">
                <div class="bg-pink-100 rounded-full p-3 w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-images text-pink-600 text-lg"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Galeri</h4>
                <p class="text-xs text-gray-600 mb-3">Kelola foto dan video</p>
                <a href="{{ route('admin.galeri.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-pink-600 hover:bg-pink-700 text-white text-xs font-medium rounded-md transition-colors">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Kelola
                </a>
            </div>
            @endif
        </div>
    </x-card>

    <!-- Management Section -->
    @if(auth()->user()->isSuperAdmin())
    <x-card>
        <x-slot:header>
            <h3 class="text-lg font-medium text-gray-900">Manajemen Sistem</h3>
        </x-slot:header>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="bg-gray-100 rounded-full p-3 w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-users text-gray-600 text-lg"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Manajemen User</h4>
                <p class="text-xs text-gray-600 mb-3">Kelola pengguna dan hak akses</p>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded-md transition-colors">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Kelola
                </a>
            </div>

            <div class="text-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <div class="bg-slate-100 rounded-full p-3 w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-cogs text-slate-600 text-lg"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Konfigurasi</h4>
                <p class="text-xs text-gray-600 mb-3">Kelola pengaturan sistem</p>
                <a href="{{ route('admin.configurations.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-slate-600 hover:bg-slate-700 text-white text-xs font-medium rounded-md transition-colors">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Kelola
                </a>
            </div>

            <div class="text-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                <div class="bg-red-100 rounded-full p-3 w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-history text-red-600 text-lg"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Audit Log</h4>
                <p class="text-xs text-gray-600 mb-3">Monitor aktivitas sistem</p>
                <a href="{{ route('admin.audit-logs.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Lihat
                </a>
            </div>
        </div>
    </x-card>
    @endif

    <!-- Recent Activity -->
    <x-card>
        <x-slot:header>
            <h3 class="text-lg font-medium text-gray-900">Aktivitas Terkini</h3>
        </x-slot:header>
        
        <div class="space-y-4">
            @if(auth()->user()->hasAnyRole(['admin_wbs', 'wbs_manager', 'admin', 'superadmin']))
            @php
                $recentWbs = \App\Models\Wbs::latest()->limit(3)->get();
            @endphp
            @if($recentWbs->count() > 0)
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Laporan WBS Terbaru</h4>
                    <div class="space-y-2">
                        @foreach($recentWbs as $wbs)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-blue-600 text-xs"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ \Illuminate\Support\Str::limit($wbs->judul, 30) }}</p>
                                    <p class="text-xs text-gray-500">{{ $wbs->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $wbs->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($wbs->status == 'resolved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($wbs->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
            @endif

            @if(auth()->user()->hasAnyRole(['admin_berita', 'content_manager', 'admin', 'superadmin']))
            @php
                $recentNews = \App\Models\PortalPapuaTengah::latest()->limit(3)->get();
            @endphp
            @if($recentNews->count() > 0)
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Berita Terbaru</h4>
                    <div class="space-y-2">
                        @foreach($recentNews as $news)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-newspaper text-purple-600 text-xs"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ \Illuminate\Support\Str::limit($news->judul, 30) }}</p>
                                    <p class="text-xs text-gray-500">{{ $news->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $news->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $news->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
            @endif

            @if(!auth()->user()->hasAnyRole(['admin_wbs', 'wbs_manager', 'admin_berita', 'content_manager', 'admin', 'superadmin']))
            <div class="text-center py-8">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-clock text-4xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Aktivitas</h4>
                <p class="text-sm text-gray-500">Aktivitas terbaru akan muncul di sini</p>
            </div>
            @endif
        </div>
    </x-card>
</div>
@endsection
