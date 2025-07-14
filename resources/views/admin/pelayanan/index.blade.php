@extends('layouts.admin')

@section('content')
<x-admin.page-header 
    title="Manajemen Pelayanan" 
    :breadcrumbs="[
        ['label' => 'Pelayanan']
    ]"
>
    <x-slot name="actions">
        <x-button 
            href="{{ route('admin.pelayanan.create') }}"
            variant="primary" 
            size="md"
        >
            <i class="fas fa-plus mr-2"></i>Tambah Layanan
        </x-button>
    </x-slot>
    
    <!-- Search and Filter -->
    <x-admin.search-filter 
        search-placeholder="Cari layanan..."
        search-value="{{ request('search') }}"
        :filters="[
            [
                'name' => 'kategori',
                'label' => 'Kategori',
                'type' => 'select',
                'placeholder' => 'Semua Kategori',
                'options' => [
                    'perizinan' => 'Perizinan',
                    'administrasi' => 'Administrasi',
                    'pengawasan' => 'Pengawasan',
                    'konsultasi' => 'Konsultasi',
                    'audit' => 'Audit'
                ]
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'placeholder' => 'Semua Status',
                'options' => [
                    '1' => 'Aktif',
                    '0' => 'Non-aktif'
                ]
            ]
        ]"
    />

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

    <x-admin.table 
        :columns="[
            [
                'key' => 'nama_layanan',
                'label' => 'Layanan',
                'render' => function($row) {
                    return '
                        <div>
                            <div class=\"text-sm font-medium text-gray-900\">' . $row->nama_layanan . '</div>
                            <div class=\"text-sm text-gray-500\">' . $row->deskripsi . '</div>
                        </div>
                    ';
                }
            ],
            [
                'key' => 'kategori',
                'label' => 'Kategori',
                'render' => function($row) {
                    $colors = [
                        'konsultasi' => 'bg-blue-100 text-blue-800',
                        'audit' => 'bg-purple-100 text-purple-800',
                        'pengawasan' => 'bg-green-100 text-green-800',
                        'perizinan' => 'bg-yellow-100 text-yellow-800',
                        'administrasi' => 'bg-gray-100 text-gray-800'
                    ];
                    $color = $colors[$row->kategori] ?? 'bg-gray-100 text-gray-800';
                    return '<span class=\"inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $color . '\">' . ucfirst($row->kategori) . '</span>';
                }
            ],
            [
                'key' => 'waktu_pelayanan',
                'label' => 'Waktu Pelayanan'
            ],
            [
                'key' => 'status',
                'label' => 'Status',
                'render' => function($row) {
                    return $row->status 
                        ? '<span class=\"inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800\">Aktif</span>'
                        : '<span class=\"inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800\">Non-aktif</span>';
                }
            ]
        ]"
        :rows="$sampleData"
        :actions="[
            [
                'type' => 'link',
                'label' => 'Lihat',
                'icon' => 'fas fa-eye',
                'url' => fn($row) => route('admin.pelayanan.show', $row->id),
                'class' => 'text-blue-600 hover:text-blue-800',
                'title' => 'Lihat Detail'
            ],
            [
                'type' => 'link',
                'label' => 'Edit',
                'icon' => 'fas fa-edit',
                'url' => fn($row) => route('admin.pelayanan.edit', $row->id),
                'class' => 'text-indigo-600 hover:text-indigo-800',
                'title' => 'Edit Layanan'
            ],
            [
                'type' => 'form',
                'label' => 'Hapus',
                'icon' => 'fas fa-trash',
                'url' => fn($row) => route('admin.pelayanan.destroy', $row->id),
                'method' => 'DELETE',
                'class' => 'text-red-600 hover:text-red-800',
                'title' => 'Hapus Layanan',
                'confirm' => 'Yakin ingin menghapus layanan ini?'
            ]
        ]"
    />

    <!-- Pagination -->
    {{-- <x-admin.pagination :paginator="$pelayanans" /> --}}
</x-admin.page-header>
@endsection
