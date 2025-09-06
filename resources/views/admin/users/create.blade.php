@extends('layouts.admin')

@section('header', 'Tambah User')

@section('breadcrumb')
<li><a href="{{                     <ul class="text-sm text-gray-600 space-y-1">
                        <li><strong>User:</strong> Akses terbatas, hanya dapat melihat informasi publik</li>
                        <li><strong>Content Admin:</strong> Dapat mengelola berita, galeri, FAQ, dan dokumen</li>
                        <li><strong>Admin:</strong> Dapat mengakses semua modul operasional kecuali manajemen user</li>
                        <li><strong>Super Admin:</strong> Akses penuh ke semua fitur termasuk manajemen user</li>
                    </ul>dmin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800">User</a></li>
<li><span class="text-gray-500">Tambah</span></li>
@endsection

@section('main-content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah User</h1>
            <p class="text-gray-600 mt-1">Tambahkan user baru ke sistem</p>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <x-card>
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi User</h3>
        </div>
        
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all duration-200 ease-in-out bg-white"
                           placeholder="Nama lengkap user">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" required
                           value="{{ old('email') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all duration-200 ease-in-out bg-white"
                           placeholder="email@example.com">
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all duration-200 ease-in-out bg-white"
                           placeholder="Minimal 8 karakter">
                    <p class="mt-1 text-sm text-gray-500">Minimal 8 karakter.</p>
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all duration-200 ease-in-out bg-white"
                           placeholder="Ulangi password">
                </div>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                    Role <span class="text-red-500">*</span>
                </label>
                <select name="role" id="role" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all duration-200 ease-in-out bg-white">
                    <option value="">Pilih Role</option>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <div class="mt-2 text-sm text-gray-600">
                    <p class="font-medium mb-1">Deskripsi Role:</p>
                    <ul class="space-y-1 text-xs">
                        <li><strong>User:</strong> Akses terbatas, hanya dapat melihat informasi publik</li>
                        <li><strong>Admin WBS:</strong> Dapat mengelola data Whistleblowing System</li>
                        <li><strong>Admin Berita:</strong> Dapat mengelola berita dan portal berita</li>
                        <li><strong>Admin Portal OPD:</strong> Dapat mengelola data Portal OPD</li>
                        <li><strong>Admin:</strong> Dapat mengakses semua modul admin kecuali manajemen user</li>
                        <li><strong>Super Admin:</strong> Akses penuh ke semua fitur termasuk manajemen user</li>
                    </ul>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}"
                   class="px-6 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
