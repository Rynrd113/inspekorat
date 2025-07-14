@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Pelayanan</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-home mr-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-chevron-right mx-2 text-gray-300"></i>
                            <span class="text-gray-600">Pelayanan</span>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex items-center space-x-3">
                <x-button 
                    href="{{ route('admin.pelayanan.create') }}"
                    variant="primary" 
                    size="md"
                >
                    <i class="fas fa-plus mr-2"></i>Tambah Layanan
                </x-button>
            </div>
        </div>
    </div>
    
    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <x-search-input 
                            name="search"
                            placeholder="Cari layanan..."
                            value="{{ request('search') }}"
                            with-icon="true"
                            size="md"
                        />
                    </div>

                    <!-- Filter Fields -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            <option value="perizinan" {{ request('kategori') == 'perizinan' ? 'selected' : '' }}>Perizinan</option>
                            <option value="administrasi" {{ request('kategori') == 'administrasi' ? 'selected' : '' }}>Administrasi</option>
                            <option value="pengawasan" {{ request('kategori') == 'pengawasan' ? 'selected' : '' }}>Pengawasan</option>
                            <option value="konsultasi" {{ request('kategori') == 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                            <option value="audit" {{ request('kategori') == 'audit' ? 'selected' : '' }}>Audit</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    <x-button type="submit" variant="primary" size="md">
                        <i class="fas fa-search mr-2"></i>Cari
                    </x-button>
                    
                    <x-button 
                        type="button" 
                        variant="secondary" 
                        size="md"
                        onclick="window.location.href='{{ route('admin.pelayanan.index') }}'"
                    >
                        <i class="fas fa-undo mr-2"></i>Reset
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Services List -->
    @php
    $sampleData = collect([
        (object)[
            'id' => 1,
            'nama_layanan' => 'Konsultasi Pengawasan',
            'deskripsi' => 'Layanan konsultasi terkait pengawasan internal',
            'kategori' => 'konsultasi',
            'waktu_pelayanan' => '3 hari kerja',
            'status' => true
        ],
        (object)[
            'id' => 2,
            'nama_layanan' => 'Audit Internal',
            'deskripsi' => 'Layanan audit internal untuk OPD',
            'kategori' => 'audit',
            'waktu_pelayanan' => '14 hari kerja',
            'status' => true
        ],
        (object)[
            'id' => 3,
            'nama_layanan' => 'Pengawasan Berkala',
            'deskripsi' => 'Layanan pengawasan berkala untuk OPD',
            'kategori' => 'pengawasan',
            'waktu_pelayanan' => '21 hari kerja',
            'status' => false
        ]
    ]);
    @endphp

    <!-- Services List -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Layanan
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Waktu Pelayanan
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($sampleData as $row)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $row->nama_layanan }}</div>
                                <div class="text-sm text-gray-500">{{ $row->deskripsi }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @php
                            $colors = [
                                'konsultasi' => 'bg-blue-100 text-blue-800',
                                'audit' => 'bg-purple-100 text-purple-800',
                                'pengawasan' => 'bg-green-100 text-green-800',
                                'perizinan' => 'bg-yellow-100 text-yellow-800',
                                'administrasi' => 'bg-gray-100 text-gray-800'
                            ];
                            $color = $colors[$row->kategori] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <x-badge variant="{{ $row->kategori }}" size="md">
                                {{ ucfirst($row->kategori) }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $row->waktu_pelayanan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($row->status)
                            <x-badge variant="success" size="md">Aktif</x-badge>
                            @else
                            <x-badge variant="danger" size="md">Non-aktif</x-badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.pelayanan.show', $row->id) }}" 
                                   class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md text-blue-600 hover:text-blue-800"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye mr-1"></i>
                                    Lihat
                                </a>
                                
                                <a href="{{ route('admin.pelayanan.edit', $row->id) }}" 
                                   class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md text-indigo-600 hover:text-indigo-800"
                                   title="Edit Layanan">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                                
                                <form method="POST" action="{{ route('admin.pelayanan.destroy', $row->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit"
                                        onclick="return confirm('Yakin ingin menghapus layanan ini?')"
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md text-red-600 hover:text-red-800"
                                        title="Hapus Layanan">
                                        <i class="fas fa-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    {{-- Add pagination here when connected to real data --}}
</div>
@endsection
