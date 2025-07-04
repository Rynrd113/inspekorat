@extends('layouts.admin')

@section('title', 'Kelola WBS - Admin Dashboard')

@section('main-content')

<!-- Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola WBS</h1>
            <p class="mt-2 text-gray-600">Kelola laporan Whistleblower System</p>
        </div>
        <div class="flex space-x-3">
            <x-button href="{{ route('admin.dashboard') }}" variant="secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </x-button>
        </div>
    </div>
</div>

            <!-- Filters -->
            <div class="mb-6">
                <x-card>
                    <div class="p-6">
                        <form method="GET" action="{{ route('admin.wbs.index') }}" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                                    <input type="text" 
                                           name="search" 
                                           id="search"
                                           value="{{ request('search') }}"
                                           placeholder="Cari berdasarkan nama, email, atau subjek..." 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select name="status" 
                                            id="status"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
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
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <x-card class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <i class="fas fa-shield-alt text-2xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-3xl font-bold">{{ $wbsReports->total() }}</div>
                            <div class="text-blue-100">Total Laporan</div>
                        </div>
                    </div>
                </x-card>

                <x-card class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <i class="fas fa-clock text-2xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-3xl font-bold">{{ $wbsReports->where('status', 'pending')->count() }}</div>
                            <div class="text-yellow-100">Pending</div>
                        </div>
                    </div>
                </x-card>

                <x-card class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <i class="fas fa-cog text-2xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-3xl font-bold">{{ $wbsReports->where('status', 'in_progress')->count() }}</div>
                            <div class="text-blue-100">In Progress</div>
                        </div>
                    </div>
                </x-card>

                <x-card class="bg-gradient-to-r from-green-500 to-teal-500 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <i class="fas fa-check text-2xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-3xl font-bold">{{ $wbsReports->where('status', 'resolved')->count() }}</div>
                            <div class="text-green-100">Resolved</div>
                        </div>
                    </div>
                </x-card>
            </div>

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
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada laporan WBS</h3>
                        <p class="text-gray-500">Laporan WBS akan muncul di sini setelah ada yang mengirim laporan melalui form WBS.</p>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
@endsection
