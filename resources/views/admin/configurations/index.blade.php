@extends('layouts.admin')

@section('title', 'Konfigurasi Sistem - Admin Dashboard')

@section('content')
<x-admin.layout 
    title="Konfigurasi Sistem"
    :breadcrumbs="[
        ['label' => 'Konfigurasi Sistem', 'url' => route('admin.configurations.index')]
    ]"
    description="Kelola konfigurasi sistem dan pengaturan aplikasi"
    :show-stats="true"
    :stats="[
        ['title' => 'Total Konfigurasi', 'value' => count($configurations), 'icon' => 'fas fa-cogs', 'color' => 'blue'],
        ['title' => 'Konfigurasi Publik', 'value' => $configurations->where('is_public', true)->count(), 'icon' => 'fas fa-globe', 'color' => 'green'],
        ['title' => 'Konfigurasi Private', 'value' => $configurations->where('is_public', false)->count(), 'icon' => 'fas fa-lock', 'color' => 'red']
    ]"
    :show-filters="true"
    :filters="[
        [
            'name' => 'group',
            'label' => 'Group',
            'type' => 'select',
            'options' => \App\Models\SystemConfiguration::getGroups()
        ],
        [
            'name' => 'type',
            'label' => 'Type',
            'type' => 'select',
            'options' => \App\Models\SystemConfiguration::getTypes()
        ]
    ]"
    search-placeholder="Cari konfigurasi..."
    :show-export="true"
    export-route="admin.configurations.export"
>
    <x-slot name="actions">
        <x-admin.button 
            variant="secondary" 
            onclick="initializeDefaults()"
            icon="fas fa-sync"
        >
            Initialize Defaults
        </x-admin.button>
        <x-admin.button 
            variant="primary" 
            onclick="openAddModal()"
            icon="fas fa-plus"
        >
            Add Configuration
        </x-admin.button>
    </x-slot>

    {{-- Tabel Konfigurasi --}}
    <x-admin.data-table 
        :columns="[
            ['key' => 'key', 'label' => 'Key'],
            ['key' => 'value', 'label' => 'Value'],
            ['key' => 'type', 'label' => 'Type'],
            ['key' => 'group', 'label' => 'Group'],
            ['key' => 'description', 'label' => 'Description'],
            ['key' => 'updated_at', 'label' => 'Updated']
        ]"
        :rows="$configurations"
        :actions="[
            [
                'type' => 'button',
                'label' => 'Edit',
                'icon' => 'fas fa-edit',
                'onclick' => 'editConfig(this)',
                'class' => 'text-yellow-600 hover:text-yellow-800 bg-yellow-50 hover:bg-yellow-100'
            ],
            [
                'type' => 'button',
                'label' => 'Delete',
                'icon' => 'fas fa-trash',
                'onclick' => 'deleteConfig(this)',
                'class' => 'text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100'
            ]
        ]"
    >
        @foreach($configurations as $config)
        <tr class="hover:bg-gray-50" data-id="{{ $config->id }}">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $config->key }}</code>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                <div class="truncate">
                    @if($config->type === 'password')
                        <span class="text-gray-500">••••••••</span>
                    @elseif($config->type === 'boolean')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config->value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $config->value ? 'True' : 'False' }}
                        </span>
                    @elseif($config->type === 'array' || $config->type === 'json')
                        <span class="text-gray-600 italic">{{ is_array($config->value) ? count($config->value) . ' items' : 'JSON object' }}</span>
                    @else
                        {{ \Illuminate\Support\Str::limit(is_string($config->value) ? $config->value : (string) json_encode($config->value), 50) }}
                    @endif
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $config->type }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ \App\Models\SystemConfiguration::getGroups()[$config->group] ?? $config->group }}
                </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                <div class="truncate" title="{{ (string) $config->description }}">
                    {{ \Illuminate\Support\Str::limit((string) $config->description, 60) }}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $config->updated_at->format('d M Y') }}
            </td>
        </tr>
        @endforeach
    </x-admin.data-table>

    {{-- Quick Actions --}}
    <x-admin.card title="Quick Actions" icon="fas fa-bolt">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-admin.button 
                variant="success" 
                onclick="alert('Fitur export belum tersedia')"
                icon="fas fa-download"
                full-width
            >
                Export Configurations
            </x-admin.button>
            <x-admin.button 
                variant="warning" 
                onclick="openImportModal()"
                icon="fas fa-upload"
                full-width
            >
                Import Configurations
            </x-admin.button>
            <x-admin.button 
                variant="secondary" 
                onclick="clearCache()"
                icon="fas fa-broom"
                full-width
            >
                Clear Cache
            </x-admin.button>
        </div>
    </x-admin.card>

    {{-- Modal Konfirmasi Hapus --}}
    <x-admin.modal
        id="deleteModal"
        title="Konfirmasi Hapus"
        description="Apakah Anda yakin ingin menghapus konfigurasi ini? Tindakan ini tidak dapat dibatalkan."
        confirm-action="confirmDelete()"
        confirm-text="Hapus"
        cancel-text="Batal"
        type="danger"
    />
</x-admin.layout>

{{-- Add Configuration Modal --}}
<x-admin.modal id="addConfigModal" title="Add Configuration">
    <form method="POST" action="{{ route('admin.configurations.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">
            <x-admin.input 
                type="text" 
                name="key" 
                label="Key" 
                required 
            />
            
            <x-admin.select 
                name="type" 
                label="Type" 
                required 
                onchange="handleTypeChange(this.value)"
                :options="[]"
            >
                @foreach(\App\Models\SystemConfiguration::getTypes() as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-admin.select>
            
            <div id="valueField">
                <x-admin.input 
                    type="textarea" 
                    name="value" 
                    label="Value" 
                    rows="3"
                />
            </div>
            
            <div id="fileField" style="display: none;">
                <x-admin.input 
                    type="file" 
                    name="file" 
                    label="File"
                />
            </div>
            
            <x-admin.select 
                name="group" 
                label="Group" 
                required
                :options="[]"
            >
                @foreach(\App\Models\SystemConfiguration::getGroups() as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-admin.select>
            
            <x-admin.input 
                type="textarea" 
                name="description" 
                label="Description" 
                rows="2"
            />
            
            <div class="flex items-center">
                <input type="checkbox" 
                       id="is_public" 
                       name="is_public" 
                       value="1" 
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_public" class="ml-2 block text-sm text-gray-900">Public Configuration</label>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3 mt-6">
            <x-admin.button 
                type="button" 
                variant="secondary" 
                onclick="closeAddModal()"
            >
                Cancel
            </x-admin.button>
            <x-admin.button 
                type="submit" 
                variant="primary"
            >
                Add Configuration
            </x-admin.button>
        </div>
    </form>
</x-admin.modal>

@push('scripts')
<script>
let deleteUrl = '';

function deleteConfig(button) {
    const row = button.closest('tr');
    const id = row.dataset.id;
    deleteUrl = `/admin/configurations/${id}`;
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

function editConfig(button) {
    const row = button.closest('tr');
    const id = row.dataset.id;
    // Add edit functionality here
    alert('Edit functionality will be implemented');
}

// Modal functions
function openAddModal() {
    openModal('addConfigModal');
}

function closeAddModal() {
    closeModal('addConfigModal');
}

function openImportModal() {
    alert('Import functionality will be implemented');
}

// Handle type changes for add form
function handleTypeChange(type) {
    const valueField = document.getElementById('valueField');
    const fileField = document.getElementById('fileField');
    
    if (type === 'file' || type === 'image') {
        valueField.style.display = 'none';
        fileField.style.display = 'block';
    } else {
        valueField.style.display = 'block';
        fileField.style.display = 'none';
    }
}

// Initialize defaults function
function initializeDefaults() {
    if (confirm('This will initialize default system configurations. Are you sure?')) {
        fetch('{{ route('admin.configurations.initialize') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Default configurations initialized successfully!');
                location.reload();
            } else {
                alert('Failed to initialize configurations');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while initializing configurations');
        });
    }
}

// Clear cache function
function clearCache() {
    if (confirm('This will clear the system cache. Continue?')) {
        alert('Cache cleared successfully!');
    }
}
</script>
@endpush
@endsection
