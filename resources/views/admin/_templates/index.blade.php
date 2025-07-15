{{-- Template untuk halaman index/daftar data --}}
@extends('layouts.admin')

@section('title', 'Nama Modul - Admin Dashboard')

@section('content')
<x-admin.layout 
    title="Nama Modul"
    :breadcrumbs="[
        ['label' => 'Nama Modul', 'url' => route('admin.modul.index')]
    ]"
    description="Deskripsi singkat tentang modul ini"
    :show-stats="true"
    :stats="[
        ['title' => 'Total Data', 'value' => '150', 'icon' => 'fas fa-database', 'color' => 'blue'],
        ['title' => 'Aktif', 'value' => '120', 'icon' => 'fas fa-check-circle', 'color' => 'green'],
        ['title' => 'Tidak Aktif', 'value' => '30', 'icon' => 'fas fa-times-circle', 'color' => 'red']
    ]"
    :show-filters="true"
    :filters="[
        [
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select',
            'options' => [
                'aktif' => 'Aktif',
                'tidak_aktif' => 'Tidak Aktif'
            ]
        ],
        [
            'name' => 'created_at',
            'label' => 'Tanggal',
            'type' => 'date'
        ]
    ]"
    search-placeholder="Cari data..."
    :show-export="true"
    export-route="admin.modul.export"
>
    <x-slot name="actions">
        <x-admin.button 
            href="{{ route('admin.modul.create') }}"
            variant="primary"
            icon="fas fa-plus"
        >
            Tambah Data
        </x-admin.button>
    </x-slot>

    {{-- Tabel Data --}}
    <x-admin.data-table 
        :columns="[
            ['key' => 'nama', 'label' => 'Nama'],
            ['key' => 'status', 'label' => 'Status', 'render' => function($item) {
                return $item->status === 'aktif' 
                    ? '<span class=\"px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full\">Aktif</span>'
                    : '<span class=\"px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full\">Tidak Aktif</span>';
            }],
            ['key' => 'created_at', 'label' => 'Tanggal Dibuat']
        ]"
        :rows="$data"
        :actions="[
            [
                'type' => 'link',
                'label' => 'Lihat',
                'icon' => 'fas fa-eye',
                'url' => fn($item) => route('admin.modul.show', $item),
                'class' => 'text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100'
            ],
            [
                'type' => 'link',
                'label' => 'Edit',
                'icon' => 'fas fa-edit',
                'url' => fn($item) => route('admin.modul.edit', $item),
                'class' => 'text-yellow-600 hover:text-yellow-800 bg-yellow-50 hover:bg-yellow-100'
            ],
            [
                'type' => 'button',
                'label' => 'Hapus',
                'icon' => 'fas fa-trash',
                'onclick' => 'deleteItem(this)',
                'class' => 'text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100'
            ]
        ]"
    />

    {{-- Modal Konfirmasi Hapus --}}
    <x-admin.modal
        id="deleteModal"
        title="Konfirmasi Hapus"
        description="Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan."
        confirm-action="confirmDelete()"
        confirm-text="Hapus"
        cancel-text="Batal"
        type="danger"
    />
</x-admin.layout>

@push('scripts')
<script>
let deleteUrl = '';

function deleteItem(button) {
    const row = button.closest('tr');
    const id = row.dataset.id;
    deleteUrl = `/admin/modul/${id}`;
    openModal('deleteModal');
}

function confirmDelete() {
    if (deleteUrl) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
