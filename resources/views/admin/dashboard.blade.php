@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('header', 'Dashboard')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
@endsection

@section('main-content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang, {{ auth()->user()->name }}</h1>
        <p class="text-gray-600">Kelola konten dan sistem Portal Inspektorat Papua Tengah</p>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @if(auth()->user()->hasAnyRole(['admin_wbs', 'wbs_manager', 'admin', 'superadmin']))
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="text-center">
                <div class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Kelola WBS</h3>
                <p class="text-gray-600 text-sm mb-4">Lihat, respons, dan kelola laporan WBS</p>
                <a href="{{ route('admin.wbs.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Kelola WBS
                </a>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasAnyRole(['admin_berita', 'content_manager', 'admin', 'superadmin']))
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="text-center">
                <div class="bg-purple-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-newspaper text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Portal Papua Tengah</h3>
                <p class="text-gray-600 text-sm mb-4">Kelola konten berita dan informasi portal</p>
                <a href="{{ route('admin.portal-papua-tengah.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Kelola Portal Papua Tengah
                </a>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasAnyRole(['admin_portal_opd', 'opd_manager', 'admin', 'superadmin']))
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="text-center">
                <div class="bg-green-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-building text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Portal OPD</h3>
                <p class="text-gray-600 text-sm mb-4">Kelola data organisasi perangkat daerah</p>
                <a href="{{ route('admin.portal-opd.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Kelola Portal OPD
                </a>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasAnyRole(['admin_faq', 'content_manager', 'admin', 'superadmin']))
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="text-center">
                <div class="bg-yellow-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-question-circle text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Kelola FAQ</h3>
                <p class="text-gray-600 text-sm mb-4">Kelola pertanyaan yang sering diajukan</p>
                <a href="{{ route('admin.faq.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Kelola FAQ
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Additional Cards for other roles -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @if(auth()->user()->hasAnyRole(['admin_pelayanan', 'service_manager', 'admin', 'superadmin']))
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="text-center">
                <div class="bg-indigo-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-concierge-bell text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Pelayanan</h3>
                <p class="text-gray-600 text-sm mb-4">Kelola informasi pelayanan publik</p>
                <a href="{{ route('admin.pelayanan.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Kelola Pelayanan
                </a>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasAnyRole(['admin_dokumen', 'service_manager', 'admin', 'superadmin']))
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="text-center">
                <div class="bg-orange-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-file-alt text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Dokumen</h3>
                <p class="text-gray-600 text-sm mb-4">Kelola dokumen dan berkas publik</p>
                <a href="{{ route('admin.dokumen.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Kelola Dokumen
                </a>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasAnyRole(['admin_galeri', 'content_manager', 'admin', 'superadmin']))
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="text-center">
                <div class="bg-pink-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-images text-pink-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Galeri</h3>
                <p class="text-gray-600 text-sm mb-4">Kelola foto dan video galeri</p>
                <a href="{{ route('admin.galeri.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Kelola Galeri
                </a>
            </div>
        </div>
        @endif

        @if(auth()->user()->isSuperAdmin())
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="text-center">
                <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-users text-gray-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Manajemen User</h3>
                <p class="text-gray-600 text-sm mb-4">Kelola pengguna dan hak akses</p>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Kelola User
                </a>
            </div>
        </div>
        @endif

        @if(auth()->user()->isSuperAdmin())
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="text-center">
                <div class="bg-slate-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-cogs text-slate-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfigurasi</h3>
                <p class="text-gray-600 text-sm mb-4">Kelola pengaturan sistem</p>
                <a href="{{ route('admin.configurations.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Kelola Konfigurasi
                </a>
            </div>
        </div>
        @endif

        @if(auth()->user()->isSuperAdmin())
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="text-center">
                <div class="bg-red-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-history text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Audit Log</h3>
                <p class="text-gray-600 text-sm mb-4">Monitor aktivitas sistem</p>
                <a href="{{ route('admin.audit-logs.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Lihat Audit Log
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
