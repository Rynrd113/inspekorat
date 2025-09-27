@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="hidden md:flex md:w-64 md:flex-col">
        <div class="flex flex-col flex-grow pt-5 overflow-y-auto" style="background: var(--brand-gradient, linear-gradient(to bottom, #1e40af, #3730a3));">
            <div class="flex items-center flex-shrink-0 px-4">
                <a href="{{ route('admin.dashboard') }}">
                    <x-site-logo variant="header" size="sm" :show-text="true" class="text-white" />
                </a>
            </div>
            
            <div class="mt-8 flex-grow flex flex-col">
                <nav class="flex-1 px-2 pb-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-blue-100 hover:bg-blue-700' }}" style="{{ request()->routeIs('admin.dashboard') ? 'background-color: var(--brand-primary, #1e40af);' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3 h-5 w-5"></i>
                        Dashboard
                    </a>
                    
                    @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    <a href="{{ route('admin.wbs.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.wbs.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="fas fa-shield-alt mr-3 h-5 w-5"></i>
                        WBS (Whistleblowing System)
                    </a>
                    @endif
                                     
                    @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    <a href="{{ route('admin.pengaduan.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.pengaduan.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="fas fa-comments mr-3 h-5 w-5"></i>
                        Pengaduan Masyarakat
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasAnyRole(['content_admin', 'admin', 'super_admin']))
                    <a href="{{ route('admin.portal-papua-tengah.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.portal-papua-tengah.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="fas fa-newspaper mr-3 h-5 w-5"></i>
                        Portal Berita
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasAnyRole(['content_admin', 'admin', 'super_admin']))
                    <a href="{{ route('admin.portal-opd.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.portal-opd.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="fas fa-building mr-3 h-5 w-5"></i>
                        Portal OPD
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasAnyRole(['content_admin', 'admin', 'super_admin']))
                    <a href="{{ route('admin.faq.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.faq.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="fas fa-question-circle mr-3 h-5 w-5"></i>
                        FAQ
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    <a href="{{ route('admin.pelayanan.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.pelayanan.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="fas fa-concierge-bell mr-3 h-5 w-5"></i>
                        Pelayanan
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasAnyRole(['content_admin', 'admin', 'super_admin']))
                    <a href="{{ route('admin.dokumen.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dokumen.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="fas fa-file-alt mr-3 h-5 w-5"></i>
                        Dokumen
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasAnyRole(['content_admin', 'admin', 'super_admin']))
                    <a href="{{ route('admin.galeri.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.galeri.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="fas fa-images mr-3 h-5 w-5"></i>
                        Galeri
                    </a>
                    @endif
                    
                    @if(auth()->user()->canApproveContent())
                    <a href="{{ route('admin.approvals.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.approvals.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="fas fa-check-circle mr-3 h-5 w-5"></i>
                        Persetujuan
                    </a>
                    @endif
                    
                    @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.users.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="fas fa-users mr-3 h-5 w-5"></i>
                        Manajemen User
                    </a>
                    @endif
                    
                    @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.configurations.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.configurations.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="fas fa-cogs mr-3 h-5 w-5"></i>
                        Konfigurasi
                    </a>
                    @endif
                    
                    @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.audit-logs.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.audit-logs.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="fas fa-history mr-3 h-5 w-5"></i>
                        Audit Log
                    </a>
                    @endif
                </nav>
            </div>
            
            <div class="flex-shrink-0 flex border-t border-blue-700 p-4">
                <div class="flex items-center">
                    <div>
                        <div class="text-sm font-medium text-white">{{ auth()->user()->name }}</div>
                        @php
                            $roleLabels = [
                                'content_admin' => 'Content Admin',
                                'admin' => 'Administrator',
                                'super_admin' => 'Super Admin'
                            ];
                        @endphp
                        <div class="text-xs text-blue-100">{{ $roleLabels[auth()->user()->role] ?? 'User' }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="ml-auto">
                        @csrf
                        <button type="submit" class="text-blue-100 hover:text-white p-2" onclick="clearAdminToken()">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="flex flex-col flex-1 overflow-hidden">
        <!-- Top navigation -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">@yield('header', 'Dashboard')</h1>
                        @hasSection('breadcrumb')
                            <nav class="flex mt-1" aria-label="Breadcrumb">
                                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                        @endif
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                            <i class="fas fa-bars h-6 w-6"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <!-- Flash Messages -->
                @if(session('success'))
                    <x-alert type="success" class="mb-6" dismissible>
                        {{ session('success') }}
                    </x-alert>
                @endif
                
                @if(session('error'))
                    <x-alert type="error" class="mb-6" dismissible>
                        {{ session('error') }}
                    </x-alert>
                @endif
                
                @if($errors->any())
                    <x-alert type="error" class="mb-6" dismissible>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif
                
                @yield('main-content')
            </div>
        </main>
    </div>
</div>
@endsection

<!-- Admin JS -->
<script src="{{ asset('js/admin.js') }}"></script>
    
<!-- Additional Scripts -->
@push('scripts')
<script>
// Store admin token in localStorage for API calls
@if(session('admin_token'))
    localStorage.setItem('admin_token', '{{ session('admin_token') }}');
    @if(config('app.debug'))
        console.log('Admin token stored successfully');
    @endif
@endif

// Check if token exists on page load
document.addEventListener('DOMContentLoaded', function() {
    const existingToken = localStorage.getItem('admin_token');
    
    if (!existingToken) {
        // Try to get token from session if not in localStorage
        @if(session('admin_token'))
            localStorage.setItem('admin_token', '{{ session('admin_token') }}');
            @if(config('app.debug'))
                console.log('Token set from session');
            @endif
        @endif
    }
    
    // Only log in debug mode
    @if(config('app.debug'))
        const currentToken = localStorage.getItem('admin_token');
        if (currentToken) {
            console.log('Admin token loaded successfully');
        } else {
            console.warn('No admin token found');
        }
    @endif
});

// Clear admin token on logout
function clearAdminToken() {
    localStorage.removeItem('admin_token');
    @if(config('app.debug'))
        console.log('Admin token cleared');
    @endif
}
</script>
@endpush
