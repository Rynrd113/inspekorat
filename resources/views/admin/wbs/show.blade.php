@extends('layouts.admin')

@section('title', 'Detail Laporan WBS')

@section('header', 'Detail Laporan WBS')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.wbs.index') }}" class="text-blue-600 hover:text-blue-800">WBS</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Detail</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Laporan WBS</h1>
            <p class="text-gray-600 mt-1">Detail lengkap laporan whistleblower</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.wbs.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
            </a>
            <a href="{{ route('admin.wbs.edit', $wbs) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Status
            </a>
        </div>
    </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Report Details -->
                    <x-card>
                        <x-slot:header>
                            <h3 class="text-lg font-semibold text-gray-900">Detail Laporan</h3>
                        </x-slot:header>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subjek Laporan</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $wbs->subjek }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Lengkap</label>
                                <div class="text-gray-900 bg-gray-50 p-4 rounded-md whitespace-pre-wrap">{{ $wbs->deskripsi }}</div>
                            </div>

                            @if($wbs->bukti_file || ($wbs->bukti_files && count($wbs->bukti_files) > 0))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">File Bukti</label>
                                    
                                    @if($wbs->bukti_file)
                                        <div class="mb-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex items-center bg-gray-50 p-3 rounded-md">
                                                    <i class="fas fa-file text-blue-600 mr-2"></i>
                                                    <span class="text-gray-900">{{ basename($wbs->bukti_file) }}</span>
                                                </div>
                                                <a href="{{ Storage::url($wbs->bukti_file) }}" 
                                                   target="_blank"
                                                   class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-download mr-1"></i>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($wbs->bukti_files && count($wbs->bukti_files) > 0)
                                        <div class="space-y-2">
                                            @foreach($wbs->bukti_files as $index => $filePath)
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex items-center bg-gray-50 p-3 rounded-md">
                                                        <i class="fas fa-file text-blue-600 mr-2"></i>
                                                        <span class="text-gray-900">File {{ $index + 1 }}: {{ basename($filePath) }}</span>
                                                    </div>
                                                    <a href="{{ Storage::url($filePath) }}" 
                                                       target="_blank"
                                                       class="text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-download mr-1"></i>
                                                        Download
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($wbs->tanggal_kejadian)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kejadian</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                        {{ \Carbon\Carbon::parse($wbs->tanggal_kejadian)->translatedFormat('l, d F Y') }}
                                    </p>
                                </div>
                            @endif

                            @if($wbs->lokasi_kejadian)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kejadian</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $wbs->lokasi_kejadian }}</p>
                                </div>
                            @endif

                            @if($wbs->pihak_terlibat)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pihak Terlibat</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $wbs->pihak_terlibat }}</p>
                                </div>
                            @endif

                            @if($wbs->kronologi)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kronologi</label>
                                    <div class="text-gray-900 bg-gray-50 p-4 rounded-md whitespace-pre-wrap">{{ $wbs->kronologi }}</div>
                                </div>
                            @endif
                        </div>
                    </x-card>

                    <!-- Response Section -->
                    @if($wbs->response)
                        <x-card>
                            <x-slot:header>
                                <h3 class="text-lg font-semibold text-gray-900">Respon Admin</h3>
                            </x-slot:header>

                            <div class="bg-blue-50 p-4 rounded-md">
                                <div class="whitespace-pre-wrap text-gray-900">{{ $wbs->response }}</div>
                                @if($wbs->responded_at)
                                    <div class="mt-3 text-sm text-gray-600">
                                        Direspon pada: {{ $wbs->responded_at->translatedFormat('l, d F Y H:i') }}
                                    </div>
                                @endif
                            </div>
                        </x-card>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <x-card>
                        <x-slot:header>
                            <h3 class="text-lg font-semibold text-gray-900">Status Laporan</h3>
                        </x-slot:header>

                        <div class="space-y-4">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'resolved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                                $statusLabels = [
                                    'pending' => 'Pending',
                                    'in_progress' => 'In Progress',
                                    'resolved' => 'Resolved',
                                    'rejected' => 'Rejected',
                                ];
                            @endphp
                            <div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$wbs->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$wbs->status] ?? ucfirst($wbs->status) }}
                                </span>
                            </div>

                            <div class="text-sm text-gray-600">
                                <strong>Dilaporkan:</strong><br>
                                {{ $wbs->created_at->translatedFormat('l, d F Y H:i') }}
                            </div>

                            @if($wbs->updated_at && $wbs->updated_at != $wbs->created_at)
                                <div class="text-sm text-gray-600">
                                    <strong>Terakhir Diupdate:</strong><br>
                                    {{ $wbs->updated_at->translatedFormat('l, d F Y H:i') }}
                                </div>
                            @endif
                        </div>
                    </x-card>

                    <!-- Reporter Info -->
                    <x-card>
                        <x-slot:header>
                            <h3 class="text-lg font-semibold text-gray-900">Informasi Pelapor</h3>
                        </x-slot:header>

                        <div class="space-y-3">
                            <div>
                                <strong class="text-sm text-gray-600">Nama:</strong>
                                <p class="text-gray-900">{{ $wbs->nama_pelapor ?: 'Anonymous' }}</p>
                            </div>

                            <div>
                                <strong class="text-sm text-gray-600">Email:</strong>
                                <p class="text-gray-900">{{ $wbs->email ?: 'Tidak disediakan' }}</p>
                            </div>

                            <div>
                                <strong class="text-sm text-gray-600">Nomor Telepon:</strong>
                                <p class="text-gray-900">{{ $wbs->nomor_telepon ?: 'Tidak disediakan' }}</p>
                            </div>

                            <div>
                                <strong class="text-sm text-gray-600">Ingin Dihubungi:</strong>
                                <p class="text-gray-900">
                                    @if($wbs->ingin_dihubungi)
                                        <span class="text-green-600">Ya</span>
                                    @else
                                        <span class="text-red-600">Tidak</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
@endsection
