@extends('layouts.admin')

@section('header', 'Manajemen Pelayanan')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Pelayanan</li>
@endsection

@section('main-content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <i class="fas fa-tools text-6xl text-yellow-500 mb-4"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">
            Dalam Tahap Pengembangan
        </h2>
        <p class="text-gray-600 mb-6">
            Halaman manajemen pelayanan sedang dalam tahap pengembangan dan akan segera tersedia.
        </p>
        <a href="{{ route('admin.dashboard') }}" 
           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
