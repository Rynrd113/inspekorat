@extends('layouts.admin')

@section('header', 'Detail User')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800">User</a></li>
<li><span class="text-gray-500">Detail</span></li>
@endsection

@section('main-content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail User</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap pengguna</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <x-card>
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Profil Pengguna</h3>
        </div>

        <div class="p-6 space-y-6">
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0 h-20 w-20">
                    <div class="h-20 w-20 rounded-full bg-blue-500 flex items-center justify-center">
                        <span class="text-white font-bold text-2xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500">{{ $user->email }}</p>
                    @php
                        $roleColors = [
                            'content_admin' => 'bg-purple-100 text-purple-800',
                            'admin' => 'bg-blue-100 text-blue-800',
                            'super_admin' => 'bg-red-100 text-red-800'
                        ];
                        $colorClass = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                        $roleLabels = \App\Models\User::getRoles();
                    @endphp
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $colorClass }} mt-1">
                        {{ $roleLabels[$user->role] ?? $user->role }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-200">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Email Terverifikasi</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($user->email_verified_at)
                            <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>{{ $user->email_verified_at->format('d M Y H:i') }}</span>
                        @else
                            <span class="text-yellow-600"><i class="fas fa-exclamation-circle mr-1"></i>Belum diverifikasi</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Status</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($user->status)
                            <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Aktif</span>
                        @else
                            <span class="text-red-600"><i class="fas fa-times-circle mr-1"></i>Non-aktif</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Role</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $roleLabels[$user->role] ?? $user->role }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Level Role</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->getRoleLevel() }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Terdaftar</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Terakhir Diperbarui</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <label class="block text-sm font-medium text-gray-500 mb-2">Modul yang Dapat Diakses</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->getAccessibleModules() as $module)
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $module }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </x-card>
</div>
@endsection
