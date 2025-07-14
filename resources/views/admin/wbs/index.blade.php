@extends('layouts.admin')

@section('title', 'Kelola WBS - Admin Dashboard')

@section('header', 'Kelola WBS')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">WBS</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan WBS</h1>
            <p class="text-gray-600 mt-1">Kelola laporan Whistleblower System dan tindak lanjut</p>
        </div>
        <div class="flex items-center space-x-3">
            <x-button href="{{ route('admin.wbs.export') }}" variant="secondary" size="md">
                <i class="fas fa-download mr-2"></i>Export Data
            </x-button>
            <x-button href="{{ route('admin.wbs.statistics') }}" variant="info" size="md">
                <i class="fas fa-chart-bar mr-2"></i>Statistik
            </x-button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $wbsReports->total() ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total Laporan</div>
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
                    <div class="text-2xl font-bold text-gray-900">{{ $wbsReports->where('status', 'pending')->count() ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Pending</div>
                </div>
            </div>
        </x-card>
        
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $wbsReports->where('status', 'resolved')->count() ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Resolved</div>
                </div>
            </div>
        </x-card>
        
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $wbsReports->where('status', 'rejected')->count() ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Rejected</div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Filters -->
    <x-card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-filter mr-2 text-blue-600"></i>Filter & Pencarian
                </h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">{{ $wbsReports->count() }} dari {{ $wbsReports->total() }} laporan</span>
                </div>
            </div>
        </x-slot:header>
        
        <form method="GET" action="{{ route('admin.wbs.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <x-search-input 
                        name="search"
                        id="search"
                        placeholder="Cari berdasarkan nama, email, atau subjek..."
                        value="{{ request('search') }}"
                        with-icon="true"
                        size="md"
                    />
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" 
                            id="status"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>🔄 In Progress</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>✅ Resolved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                    </select>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Prioritas</label>
                    <select name="priority" 
                            id="priority"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        <option value="">Semua Prioritas</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>🔴 Tinggi</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>🟡 Sedang</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>🟢 Rendah</option>
                    </select>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <x-button type="submit" variant="primary" size="md">
                        <i class="fas fa-search mr-2"></i>Cari Laporan
                    </x-button>
                    
                    <x-button 
                        type="button" 
                        variant="secondary" 
                        size="md"
                        onclick="window.location.href='{{ route('admin.wbs.index') }}'"
                    >
                        <i class="fas fa-undo mr-2"></i>Reset Filter
                    </x-button>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Urut:</span>
                    <select name="sort" class="px-3 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>Prioritas</option>
                        <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>
            </div>
        </form>
    </x-card>
                                <div class="flex items-end">
                                    <x-button type="submit" class="w-full">
                                        <i class="fas fa-search mr-2"></i>
                                        Filter
                                    </x-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </x-card>

    <!-- WBS Reports Table -->
            <x-card>
                <x-slot:header>
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Laporan WBS</h3>
                </x-slot:header>

                @if($wbsReports->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pelapor
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subjek
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($wbsReports as $wbs)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $wbs->nama_pelapor ?: 'Anonymous' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $wbs->email ?: 'Tidak ada email' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ Str::limit($wbs->subjek, 50) }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($wbs->deskripsi, 80) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$wbs->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$wbs->status] ?? ucfirst($wbs->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $wbs->created_at->translatedFormat('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.wbs.show', $wbs) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.wbs.edit', $wbs) }}" 
                                               class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.wbs.destroy', $wbs) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $wbsReports->appends(request()->query())->links() }}
                    </div>
                @else
                    <x-empty-state
                        title="Belum ada laporan WBS"
                        description="Laporan WBS akan muncul di sini setelah ada yang mengirim laporan melalui form WBS."
                        icon="fas fa-shield-alt"
                        suggestion="Pastikan form WBS sudah tersedia untuk masyarakat di website publik."
                    />
                @endif
            </x-card>
        </div>
    </div>
@endsection
