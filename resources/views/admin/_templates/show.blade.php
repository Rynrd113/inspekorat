{{-- Template untuk halaman show/detail data --}}
@extends('layouts.admin')

@section('title', 'Detail Nama Modul - Admin Dashboard')

@section('content')
<x-admin.layout 
    title="Detail Nama Modul"
    :breadcrumbs="[
        ['label' => 'Nama Modul', 'url' => route('admin.modul.index')],
        ['label' => 'Detail']
    ]"
    description="Informasi lengkap tentang data ini"
>
    <x-slot name="actions">
        <x-admin.button 
            href="{{ route('admin.modul.edit', $item) }}"
            variant="primary"
            icon="fas fa-edit"
        >
            Edit
        </x-admin.button>
        
        <x-admin.button 
            href="{{ route('admin.modul.index') }}"
            variant="secondary"
            icon="fas fa-arrow-left"
        >
            Kembali
        </x-admin.button>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Detail Info --}}
        <div class="lg:col-span-2">
            <x-admin.card title="Informasi Detail" icon="fas fa-info-circle">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $item->nama }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-1">
                            @if($item->status === 'aktif')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($item->deskripsi)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $item->deskripsi }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dibuat</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $item->created_at->format('d F Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Terakhir Diubah</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $item->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </x-admin.card>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-6">
            {{-- Gambar --}}
            @if($item->gambar)
            <x-admin.card title="Gambar" icon="fas fa-image">
                <img src="{{ $item->gambar_url }}" alt="{{ $item->nama }}" class="w-full h-auto rounded">
            </x-admin.card>
            @endif
            
            {{-- Quick Actions --}}
            <x-admin.card title="Tindakan Cepat" icon="fas fa-bolt">
                <div class="space-y-3">
                    <x-admin.button 
                        href="{{ route('admin.modul.edit', $item) }}"
                        variant="primary"
                        icon="fas fa-edit"
                        full-width
                    >
                        Edit Data
                    </x-admin.button>
                    
                    <x-admin.button 
                        onclick="openModal('deleteModal')"
                        variant="danger"
                        icon="fas fa-trash"
                        full-width
                    >
                        Hapus Data
                    </x-admin.button>
                </div>
            </x-admin.card>
            
            {{-- Metadata --}}
            <x-admin.card title="Metadata" icon="fas fa-info">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">ID:</span>
                        <span class="font-medium">{{ $item->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dibuat:</span>
                        <span class="font-medium">{{ $item->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Diubah:</span>
                        <span class="font-medium">{{ $item->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </x-admin.card>
        </div>
    </div>

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
function confirmDelete() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.modul.destroy", $item) }}';
    form.innerHTML = `
        @csrf
        @method('DELETE')
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
@endsection
