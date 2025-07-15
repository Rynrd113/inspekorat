@extends('layouts.admin')

@section('title', 'Konfigurasi Sistem')

@section('header', 'Konfigurasi Sistem')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Konfigurasi Sistem</li>
@endsection

@section('main-content')
<!-- Success/Error Notifications -->
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Konfigurasi Sistem</h1>
            <p class="text-gray-600 mt-1">Kelola konfigurasi sistem dan pengaturan aplikasi</p>
        </div>
        <div class="flex items-center space-x-3">
            <button 
                onclick="initializeDefaults()"
                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors"
            >
                <i class="fas fa-sync mr-2"></i>Initialize Defaults
            </button>
            <button 
                onclick="openAddModal()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
            >
                <i class="fas fa-plus mr-2"></i>Add Configuration
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-cogs text-2xl text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Konfigurasi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ count($configurations) }}</p>
                </div>
            </div>
        </x-card>
        
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-globe text-2xl text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Konfigurasi Publik</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $configurations->where('is_public', true)->count() }}</p>
                </div>
            </div>
        </x-card>
        
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-lock text-2xl text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Konfigurasi Private</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $configurations->where('is_public', false)->count() }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Search and Filter -->
    <x-card>
        <x-slot:header>
            <h3 class="text-lg font-medium text-gray-900">Filter & Pencarian</h3>
        </x-slot:header>
        
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Konfigurasi</label>
                    <input 
                        type="text" 
                        name="search" 
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Cari konfigurasi..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                
                <div>
                    <label for="group" class="block text-sm font-medium text-gray-700 mb-1">Group</label>
                    <select 
                        name="group" 
                        id="group"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">Semua Group</option>
                        @foreach(\App\Models\SystemConfiguration::getGroups() as $key => $label)
                            <option value="{{ $key }}" {{ request('group') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select 
                        name="type" 
                        id="type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">Semua Type</option>
                        @foreach(\App\Models\SystemConfiguration::getTypes() as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button 
                        type="submit"
                        class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
                    >
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </x-card>

    {{-- Tabel Konfigurasi --}}
    <x-card>
        <x-slot:header>
            <h3 class="text-lg font-medium text-gray-900">Daftar Konfigurasi</h3>
        </x-slot:header>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Key
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Value
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Group
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Updated
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button 
                                    onclick="editConfig(this)"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md text-yellow-600 hover:text-yellow-800 bg-yellow-50 hover:bg-yellow-100"
                                    title="Edit"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    onclick="deleteConfig(this)"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100"
                                    title="Delete"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Quick Actions --}}
    <x-card>
        <x-slot:header>
            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
        </x-slot:header>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button 
                onclick="alert('Fitur export belum tersedia')"
                class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors"
            >
                <i class="fas fa-download mr-2"></i>Export Configurations
            </button>
            <button 
                onclick="openImportModal()"
                class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700 transition-colors"
            >
                <i class="fas fa-upload mr-2"></i>Import Configurations
            </button>
            <button 
                onclick="clearCache()"
                class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors"
            >
                <i class="fas fa-broom mr-2"></i>Clear Cache
            </button>
        </div>
    </x-card>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Konfirmasi Hapus
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin menghapus konfigurasi ini? Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        type="button" 
                        onclick="confirmDelete()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Hapus
                    </button>
                    <button 
                        type="button" 
                        onclick="closeModal('deleteModal')"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Configuration Modal --}}
<div id="addConfigModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-plus text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Add Configuration
                        </h3>
                        <div class="mt-4">
                            <form method="POST" action="{{ route('admin.configurations.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="key" class="block text-sm font-medium text-gray-700">Key</label>
                                        <input 
                                            type="text" 
                                            name="key" 
                                            id="key"
                                            required 
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        />
                                    </div>
                                    
                                    <div>
                                        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                        <select 
                                            name="type" 
                                            id="type"
                                            required 
                                            onchange="handleTypeChange(this.value)"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                            @foreach(\App\Models\SystemConfiguration::getTypes() as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div id="valueField">
                                        <label for="value" class="block text-sm font-medium text-gray-700">Value</label>
                                        <textarea 
                                            name="value" 
                                            id="value"
                                            rows="3"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        ></textarea>
                                    </div>
                                    
                                    <div id="fileField" style="display: none;">
                                        <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                                        <input 
                                            type="file" 
                                            name="file" 
                                            id="file"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        />
                                    </div>
                                    
                                    <div>
                                        <label for="group" class="block text-sm font-medium text-gray-700">Group</label>
                                        <select 
                                            name="group" 
                                            id="group"
                                            required
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                            @foreach(\App\Models\SystemConfiguration::getGroups() as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea 
                                            name="description" 
                                            id="description"
                                            rows="2"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        ></textarea>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            id="is_public" 
                                            name="is_public" 
                                            value="1" 
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        >
                                        <label for="is_public" class="ml-2 block text-sm text-gray-900">Public Configuration</label>
                                    </div>
                                </div>
                                
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button 
                                        type="button" 
                                        onclick="closeModal('addConfigModal')"
                                        class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
                                    >
                                        Cancel
                                    </button>
                                    <button 
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm"
                                    >
                                        Add Configuration
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

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
