@extends('layouts.app')

@section('title', 'Admin Login - Portal Inspektorat Papua Tengah')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="flex justify-center">
                <div class="bg-white p-4 rounded-full shadow-lg">
                    <div class="h-12 w-12 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-lg font-bold">PT</span>
                    </div>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-white">
                Admin Panel
            </h2>
            <p class="mt-2 text-center text-sm text-blue-100">
                Portal Inspektorat Papua Tengah
            </p>
        </div>

        <x-card class="shadow-2xl">
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

            <form class="space-y-6" action="{{ route('admin.login.submit') }}" method="POST" id="admin-login-form">
                @csrf
                <div>
                    <x-input 
                        type="email" 
                        name="email" 
                        label="Email Administrator" 
                        placeholder="Masukkan email Anda"
                        required 
                        id="email"
                        value="{{ old('email') }}"
                        :error="$errors->first('email')"
                    />
                </div>

                <div>
                    <x-input 
                        type="password" 
                        name="password" 
                        label="Password" 
                        placeholder="Masukkan password"
                        required 
                        id="password"
                        :error="$errors->first('password')"
                    />
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            Ingat saya
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-green-600 hover:text-green-500">
                            Lupa password?
                        </a>
                    </div>
                </div>

                <div>
                    <x-button type="submit" variant="primary" size="lg" class="w-full">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Masuk ke Admin Panel
                    </x-button>
                </div>
            </form>

            <div class="mt-6 border-t border-gray-200 pt-6">
                <div class="text-center">
                    <a href="{{ route('public.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Check if we have success message and admin token in session
@if(session('success') && session('admin_token'))
    // Store token in localStorage for API calls
    localStorage.setItem('admin_token', '{{ session('admin_token') }}');
    console.log('Admin token stored successfully');
@endif

// Also handle form submission via AJAX if needed
document.getElementById('admin-login-form')?.addEventListener('submit', function(e) {
    // Let the form submit normally for now
    // We can add AJAX handling later if needed
});
</script>
@endpush
