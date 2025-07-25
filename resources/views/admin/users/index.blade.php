@extends('layouts.admin')

@section('header', 'Manajemen User')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">User</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar User</h1>
            <p class="text-gray-600 mt-1">Kelola pengguna dan hak akses sistem</p>
        </div>
        <x-button 
            href="{{ route('admin.users.create') }}"
            variant="primary" 
            size="md"
        >
            <i class="fas fa-plus mr-2"></i>Tambah User
        </x-button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total User</div>
                </div>
            </div>
        </x-card>
        
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ \App\Models\User::whereNotNull('email_verified_at')->count() ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Terverifikasi</div>
                </div>
            </div>
        </x-card>
        
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ \App\Models\User::whereIn('role', ['admin', 'superadmin'])->count() ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Admin</div>
                </div>
            </div>
        </x-card>
        
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ \App\Models\User::where('created_at', '>=', now()->subDays(7))->count() ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Baru (7 hari)</div>
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
        
        <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <x-search-input 
                            name="search"
                            placeholder="Nama atau email..."
                            value="{{ request('search') }}"
                            with-icon="true"
                            size="md"
                        />
                    </div>

                    <!-- Filter Fields -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Role</option>
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-3">
                        <x-button type="submit" variant="primary" size="md">
                            <i class="fas fa-search mr-2"></i>Cari User
                        </x-button>
                        
                        <x-button 
                            type="button" 
                            variant="secondary" 
                            size="md"
                            onclick="window.location.href='{{ route('admin.users.index') }}'"
                        >
                            <i class="fas fa-undo mr-2"></i>Reset Filter
                        </x-button>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Urut:</span>
                        <select name="sort" class="px-3 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="role" {{ request('sort') == 'role' ? 'selected' : '' }}>Role</option>
                        </select>
                    </div>
                </div>
            </form>
    </x-card>

    <!-- Users List -->
    <x-card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-users mr-2 text-blue-600"></i>Daftar User
                </h2>
                <div class="text-sm text-gray-500">
                    Total: {{ $users->total() ?? 0 }} user
                </div>
            </div>
        </x-slot:header>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terdaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                        <span class="text-white font-medium text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $roleColors = [
                                    'user' => 'bg-gray-100 text-gray-800',
                                    'admin_wbs' => 'bg-purple-100 text-purple-800',
                                    'admin_berita' => 'bg-green-100 text-green-800',
                                    'admin_portal_opd' => 'bg-yellow-100 text-yellow-800',
                                    'admin' => 'bg-blue-100 text-blue-800',
                                    'superadmin' => 'bg-red-100 text-red-800'
                                ];
                                $colorClass = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $colorClass }}">
                                {{ $roles[$user->role] ?? $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-blue-600 hover:text-blue-900"
                                   title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="text-indigo-600 hover:text-indigo-900"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900"
                                            title="Hapus"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4">
                            @if(request('search') || request('role') || request('status'))
                                <x-empty-state
                                    title="Tidak ada user yang sesuai filter"
                                    description="Tidak ditemukan user yang cocok dengan pencarian atau filter yang Anda gunakan."
                                    icon="fas fa-search"
                                    :action="true"
                                    actionText="Reset Filter"
                                    actionUrl="{{ route('admin.users.index') }}"
                                    actionVariant="secondary"
                                    suggestion="Coba gunakan kata kunci yang berbeda atau ubah filter pencarian."
                                />
                            @else
                                <x-empty-state
                                    title="Belum ada user terdaftar"
                                    description="Mulai dengan menambahkan user pertama untuk mengelola sistem."
                                    icon="fas fa-users"
                                    :action="true"
                                    actionText="Tambah User"
                                    actionUrl="{{ route('admin.users.create') }}"
                                    suggestion="User pertama sebaiknya memiliki role admin untuk mengelola sistem."
                                />
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </x-card>

    <!-- Pagination -->
    {{-- Add pagination here when connected to real data --}}
</div>
@endsection
