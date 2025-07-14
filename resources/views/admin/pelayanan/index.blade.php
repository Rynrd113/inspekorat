@extends('layouts.admin')

@section('header', 'Manajemen Pelayanan')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Pelayanan</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Pelayanan</h1>
            <p class="text-gray-600 mt-1">Kelola layanan publik yang disediakan oleh Inspektorat</p>
        </div>
        <x-button 
            href="{{ route('admin.pelayanan.create') }}"
            variant="primary" 
            size="md"
        >
            <i class="fas fa-plus mr-2"></i>Tambah Layanan
        </x-button>
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
                        placeholder="Cari layanan..."
                        value="{{ request('search') }}"
                        with-icon="true"
                        size="md"
                    />
                </div>

                <!-- Filter Fields -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
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
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Non-aktif</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-gray-200">
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
    </x-card>

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
    <x-card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-concierge-bell mr-2 text-blue-600"></i>Daftar Layanan
                </h2>
                <div class="text-sm text-gray-500">
                    Total: {{ $sampleData->count() }} layanan
                </div>
            </div>
        </x-slot:header>
        
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
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.pelayanan.show', $row->id) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.pelayanan.edit', $row->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Edit Layanan">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form method="POST" action="{{ route('admin.pelayanan.destroy', $row->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Yakin ingin menghapus layanan ini?')"
                                            title="Hapus Layanan">
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
        
        @if($sampleData->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada layanan</h3>
            <p class="text-gray-500 mb-4">Mulai dengan menambahkan layanan pertama.</p>
            <x-button 
                href="{{ route('admin.pelayanan.create') }}"
                variant="primary" 
                size="md"
            >
                <i class="fas fa-plus mr-2"></i>Tambah Layanan
            </x-button>
        </div>
        @endif
    </x-card>

    <!-- Pagination -->
    {{-- Add pagination here when connected to real data --}}
</div>
@endsection
