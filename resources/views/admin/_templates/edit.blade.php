{{-- Template untuk halaman edit/ubah data --}}
@extends('layouts.admin')

@section('title', 'Edit Nama Modul - Admin Dashboard')

@section('content')
<x-admin.layout 
    title="Edit Nama Modul"
    :breadcrumbs="[
        ['label' => 'Nama Modul', 'url' => route('admin.modul.index')],
        ['label' => 'Edit']
    ]"
    description="Edit data untuk modul ini"
>
    <x-slot name="actions">
        <x-admin.button 
            href="{{ route('admin.modul.index') }}"
            variant="secondary"
            icon="fas fa-arrow-left"
        >
            Kembali
        </x-admin.button>
    </x-slot>

    {{-- Form Layout --}}
    <x-admin.card title="Form Edit Data" icon="fas fa-edit">
        <form method="POST" action="{{ route('admin.modul.update', $item) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Input Field --}}
                <x-admin.input 
                    name="nama"
                    label="Nama"
                    placeholder="Masukkan nama..."
                    required
                    :value="old('nama', $item->nama)"
                    help="Nama harus unik dan tidak boleh kosong"
                />

                {{-- Select Field --}}
                <x-admin.select 
                    name="status"
                    label="Status"
                    required
                    :options="[
                        'aktif' => 'Aktif',
                        'tidak_aktif' => 'Tidak Aktif'
                    ]"
                    :value="old('status', $item->status)"
                />

                {{-- Textarea Field --}}
                <div class="md:col-span-2">
                    <x-admin.input 
                        type="textarea"
                        name="deskripsi"
                        label="Deskripsi"
                        placeholder="Masukkan deskripsi..."
                        rows="4"
                        :value="old('deskripsi', $item->deskripsi)"
                    />
                </div>

                {{-- File Upload --}}
                <div class="md:col-span-2">
                    <x-admin.input 
                        type="file"
                        name="gambar"
                        label="Gambar"
                        accept="image/*"
                        help="Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar"
                    />
                    
                    @if($item->gambar)
                    <div class="mt-2">
                        <img src="{{ $item->gambar_url }}" alt="Gambar saat ini" class="w-20 h-20 object-cover rounded">
                        <p class="text-sm text-gray-500 mt-1">Gambar saat ini</p>
                    </div>
                    @endif
                </div>

                {{-- Checkbox --}}
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="is_featured" 
                            name="is_featured" 
                            value="1"
                            {{ old('is_featured', $item->is_featured) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                            Jadikan sebagai unggulan
                        </label>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <x-admin.button 
                    href="{{ route('admin.modul.index') }}"
                    variant="secondary"
                >
                    Batal
                </x-admin.button>
                
                <x-admin.button 
                    type="submit"
                    variant="primary"
                    icon="fas fa-save"
                >
                    Update Data
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin.layout>
@endsection
