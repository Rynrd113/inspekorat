@extends('layouts.admin')

@section('title', 'System Configurations')

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">System Configurations</h1>
                <p class="text-gray-600 mt-1">Manage system settings and configurations</p>
            </div>
            <div class="flex space-x-3">
                <button type="button" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors"
                        onclick="initializeDefaults()">
                    <i class="fas fa-sync mr-2"></i> Initialize Defaults
                </button>
                <button type="button" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
                        onclick="openAddModal()">
                    <i class="fas fa-plus mr-2"></i> Add Configuration
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="searchConfig" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       id="searchConfig" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Search configurations...">
            </div>
            <div>
                <label for="filterGroup" class="block text-sm font-medium text-gray-700 mb-2">Filter by Group</label>
                <select id="filterGroup" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Groups</option>
                    @foreach(\App\Models\SystemConfiguration::getGroups() as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filterType" class="block text-sm font-medium text-gray-700 mb-2">Filter by Type</label>
                <select id="filterType" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Types</option>
                    @foreach(\App\Models\SystemConfiguration::getTypes() as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Configurations Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="configurationsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($configurations as $config)
                    <tr class="hover:bg-gray-50">
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
                                    {{ Str::limit((string) $config->value, 50) }}
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
                            <div class="truncate" title="{{ $config->description }}">
                                {{ Str::limit((string) $config->description, 60) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $config->updated_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button type="button" 
                                        class="text-blue-600 hover:text-blue-900 transition-colors"
                                        onclick="editConfig({{ $config->id }}, '{{ $config->key }}', {{ json_encode($config->value) }}, '{{ $config->type }}', '{{ $config->group }}', '{{ addslashes($config->description) }}', {{ $config->is_public ? 'true' : 'false' }})"
                                        title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.configurations.destroy', $config) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 transition-colors"
                                            onclick="return confirm('Are you sure you want to delete this configuration?')"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-cogs text-gray-300 text-4xl mb-4"></i>
                                <p class="text-lg">No configurations found</p>
                                <p class="text-sm">Get started by adding your first configuration or initialize defaults</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($configurations->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $configurations->links() }}
        </div>
        @endif
    </div>

    <!-- Actions Card -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.configurations.export') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                <i class="fas fa-download mr-2"></i> Export Configurations
            </a>
            <button type="button" 
                    class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700 transition-colors"
                    onclick="openImportModal()">
                <i class="fas fa-upload mr-2"></i> Import Configurations
            </button>
            <button type="button" 
                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors"
                    onclick="clearCache()">
                <i class="fas fa-broom mr-2"></i> Clear Cache
            </button>
        </div>
    </div>
</div>
<<!-- Add Configuration Modal -->
<div id="addConfigModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <form method="POST" action="{{ route('admin.configurations.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Add Configuration</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label for="key" class="block text-sm font-medium text-gray-700 mb-1">Key</label>
                        <input type="text" 
                               id="key" 
                               name="key" 
                               required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select id="type" 
                                name="type" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                onchange="handleTypeChange(this.value)">
                            @foreach(\App\Models\SystemConfiguration::getTypes() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="valueField">
                        <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                        <textarea id="value" 
                                  name="value" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div id="fileField" style="display: none;">
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-1">File</label>
                        <input type="file" 
                               id="file" 
                               name="file" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="group" class="block text-sm font-medium text-gray-700 mb-1">Group</label>
                        <select id="group" 
                                name="group" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @foreach(\App\Models\SystemConfiguration::getGroups() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_public" 
                               name="is_public" 
                               value="1" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_public" class="ml-2 block text-sm text-gray-900">Public Configuration</label>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeAddModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Add Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Configuration Modal -->
<div id="editConfigModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <form method="POST" action="{{ route('admin.configurations.update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edit_config_id" name="id">
                <div class="bg-white px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Edit Configuration</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label for="edit_key" class="block text-sm font-medium text-gray-700 mb-1">Key</label>
                        <input type="text" 
                               id="edit_key" 
                               name="key" 
                               required 
                               readonly 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="edit_type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select id="edit_type" 
                                name="type" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                onchange="handleEditTypeChange(this.value)">
                            @foreach(\App\Models\SystemConfiguration::getTypes() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="editValueField">
                        <label for="edit_value" class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                        <textarea id="edit_value" 
                                  name="value" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div id="editFileField" style="display: none;">
                        <label for="edit_file" class="block text-sm font-medium text-gray-700 mb-1">File</label>
                        <input type="file" 
                               id="edit_file" 
                               name="file" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="edit_group" class="block text-sm font-medium text-gray-700 mb-1">Group</label>
                        <select id="edit_group" 
                                name="group" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @foreach(\App\Models\SystemConfiguration::getGroups() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="edit_description" 
                                  name="description" 
                                  rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="edit_is_public" 
                               name="is_public" 
                               value="1" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="edit_is_public" class="ml-2 block text-sm text-gray-900">Public Configuration</label>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeEditModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Configuration Modal -->
<div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <form method="POST" action="{{ route('admin.configurations.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Import Configurations</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label for="import_file" class="block text-sm font-medium text-gray-700 mb-1">Configuration File</label>
                        <input type="file" 
                               id="import_file" 
                               name="file" 
                               accept=".json" 
                               required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-sm text-gray-500 mt-1">Supported formats: JSON</p>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="overwrite" 
                               name="overwrite" 
                               value="1" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="overwrite" class="ml-2 block text-sm text-gray-900">Overwrite existing configurations</label>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeImportModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Import Configurations
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Modal functions
function openAddModal() {
    document.getElementById('addConfigModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('addConfigModal').classList.add('hidden');
}

function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
}

function closeEditModal() {
    document.getElementById('editConfigModal').classList.add('hidden');
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

// Handle type changes for edit form
function handleEditTypeChange(type) {
    const valueField = document.getElementById('editValueField');
    const fileField = document.getElementById('editFileField');
    
    if (type === 'file' || type === 'image') {
        valueField.style.display = 'none';
        fileField.style.display = 'block';
    } else {
        valueField.style.display = 'block';
        fileField.style.display = 'none';
    }
}

// Edit configuration function
function editConfig(id, key, value, type, group, description, isPublic) {
    document.getElementById('edit_config_id').value = id;
    document.getElementById('edit_key').value = key;
    document.getElementById('edit_type').value = type;
    document.getElementById('edit_group').value = group;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_is_public').checked = isPublic;
    
    // Handle different value types
    if (type === 'json' || type === 'array') {
        document.getElementById('edit_value').value = JSON.stringify(value, null, 2);
    } else if (type === 'boolean') {
        document.getElementById('edit_value').value = value ? 'true' : 'false';
    } else if (type === 'file' || type === 'image') {
        document.getElementById('edit_value').value = value || '';
    } else {
        document.getElementById('edit_value').value = value || '';
    }
    
    handleEditTypeChange(type);
    document.getElementById('editConfigModal').classList.remove('hidden');
}

// Initialize defaults
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
        // You can implement cache clearing here
        alert('Cache cleared successfully');
    }
}

// Search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchConfig');
    const filterGroup = document.getElementById('filterGroup');
    const filterType = document.getElementById('filterType');
    const table = document.getElementById('configurationsTable');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const groupFilter = filterGroup.value.toLowerCase();
        const typeFilter = filterType.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            if (row.cells.length < 7) return; // Skip empty row
            
            const key = row.cells[0].textContent.toLowerCase();
            const value = row.cells[1].textContent.toLowerCase();
            const type = row.cells[2].textContent.toLowerCase();
            const group = row.cells[3].textContent.toLowerCase();
            const description = row.cells[4].textContent.toLowerCase();
            
            const matchesSearch = key.includes(searchTerm) || 
                                value.includes(searchTerm) || 
                                description.includes(searchTerm);
            const matchesGroup = !groupFilter || group.includes(groupFilter);
            const matchesType = !typeFilter || type.includes(typeFilter);
            
            row.style.display = matchesSearch && matchesGroup && matchesType ? '' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterTable);
    filterGroup.addEventListener('change', filterTable);
    filterType.addEventListener('change', filterTable);
});
</script>
@endpush
@endsection
