@extends('layouts.admin')

@section('title', 'Edit WBS - Admin Dashboard')

@section('main-content')

<!-- Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Status WBS</h1>
            <p class="mt-2 text-gray-600">Update status dan berikan respon untuk laporan WBS</p>
        </div>
        <div class="flex space-x-3">
            <x-button href="{{ route('admin.wbs.show', $wbs) }}" variant="secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Detail
            </x-button>
        </div>
    </div>
</div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Edit Form -->
                <div class="lg:col-span-2">
                    <x-card>
                        <x-slot:header>
                            <h3 class="text-lg font-semibold text-gray-900">Update Status & Respon</h3>
                        </x-slot:header>

                        <form action="{{ route('admin.wbs.update', $wbs) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Laporan</label>
                                <select name="status" 
                                        id="status" 
                                        required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="pending" {{ $wbs->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $wbs->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $wbs->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="rejected" {{ $wbs->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Response -->
                            <div>
                                <label for="response" class="block text-sm font-medium text-gray-700 mb-2">Respon Admin</label>
                                <textarea name="response" 
                                          id="response" 
                                          rows="6"
                                          placeholder="Berikan respon atau keterangan terkait status laporan..."
                                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('response', $wbs->response) }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Respon akan dikirim ke pelapor jika email disediakan.</p>
                                @error('response')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end space-x-3">
                                <x-button type="button" 
                                          variant="secondary" 
                                          onclick="window.history.back()">
                                    Batal
                                </x-button>
                                <x-button type="submit">
                                    <i class="fas fa-save mr-2"></i>
                                    Update Status
                                </x-button>
                            </div>
                        </form>
                    </x-card>
                </div>

                <!-- Report Summary Sidebar -->
                <div class="space-y-6">
                    <!-- Report Summary -->
                    <x-card>
                        <x-slot:header>
                            <h3 class="text-lg font-semibold text-gray-900">Ringkasan Laporan</h3>
                        </x-slot:header>

                        <div class="space-y-4">
                            <div>
                                <strong class="text-sm text-gray-600">Subjek:</strong>
                                <p class="text-gray-900 text-sm">{{ Str::limit($wbs->subjek, 100) }}</p>
                            </div>

                            <div>
                                <strong class="text-sm text-gray-600">Pelapor:</strong>
                                <p class="text-gray-900 text-sm">{{ $wbs->nama_pelapor ?: 'Anonymous' }}</p>
                            </div>

                            <div>
                                <strong class="text-sm text-gray-600">Email:</strong>
                                <p class="text-gray-900 text-sm">{{ $wbs->email ?: 'Tidak disediakan' }}</p>
                            </div>

                            <div>
                                <strong class="text-sm text-gray-600">Tanggal Laporan:</strong>
                                <p class="text-gray-900 text-sm">{{ $wbs->created_at->translatedFormat('d M Y H:i') }}</p>
                            </div>

                            @if($wbs->tanggal_kejadian)
                                <div>
                                    <strong class="text-sm text-gray-600">Tanggal Kejadian:</strong>
                                    <p class="text-gray-900 text-sm">{{ \Carbon\Carbon::parse($wbs->tanggal_kejadian)->translatedFormat('d M Y') }}</p>
                                </div>
                            @endif

                            @if($wbs->lokasi_kejadian)
                                <div>
                                    <strong class="text-sm text-gray-600">Lokasi:</strong>
                                    <p class="text-gray-900 text-sm">{{ Str::limit($wbs->lokasi_kejadian, 50) }}</p>
                                </div>
                            @endif
                        </div>
                    </x-card>

                    <!-- Current Status -->
                    <x-card>
                        <x-slot:header>
                            <h3 class="text-lg font-semibold text-gray-900">Status Saat Ini</h3>
                        </x-slot:header>

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

                        <div class="text-center">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusClasses[$wbs->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$wbs->status] ?? ucfirst($wbs->status) }}
                            </span>
                        </div>

                        @if($wbs->response)
                            <div class="mt-4">
                                <strong class="text-sm text-gray-600">Respon Sebelumnya:</strong>
                                <div class="mt-2 p-3 bg-gray-50 rounded-md text-sm text-gray-900">
                                    {{ Str::limit($wbs->response, 150) }}
                                </div>
                            </div>
                        @endif
                    </x-card>
                </div>
            </div>
@endsection
