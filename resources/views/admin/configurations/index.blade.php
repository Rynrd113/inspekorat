@extends('layouts.admin')

@section('title', 'System Configurations')

@section('header', 'System Configurations')
@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><span class="text-gray-500">System Configurations</span></li>
@endsection

@section('main-content')
<x-card>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-900">System Configurations</h2>
        <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Add Configuration
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($configurations ?? [] as $config)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <code>{{ $config->key }}</code>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ Str::limit($config->value, 50) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $config->group ?? 'default' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ Str::limit($config->description, 60) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-1">
                            <button type="button" class="text-blue-600 hover:text-blue-900" onclick="alert('Edit functionality coming soon')">>
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.configurations.destroy', $config) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this configuration?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No configurations found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
@endsection
<div class="container-fluid px-4">
    <h1 class="mt-4">System Configurations</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">System Configurations</li>
    </ol>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-cogs me-1"></i>
                        Configuration Settings
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addConfigModal">
                            <i class="fas fa-plus me-1"></i> Add Configuration
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="searchConfig" placeholder="Search configurations...">
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="filterGroup">
                                <option value="">All Groups</option>
                                <option value="app">Application</option>
                                <option value="mail">Mail</option>
                                <option value="cache">Cache</option>
                                <option value="session">Session</option>
                                <option value="database">Database</option>
                                <option value="queue">Queue</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="configurationsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Key</th>
                                    <th>Value</th>
                                    <th>Group</th>
                                    <th>Description</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($configurations as $config)
                                <tr>
                                    <td>
                                        <code>{{ $config->key }}</code>
                                    </td>
                                    <td>
                                        <span class="config-value" data-full-value="{{ $config->value }}">
                                            {{ Str::limit($config->value, 50) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $config->group ?? 'default' }}</span>
                                    </td>
                                    <td>{{ Str::limit($config->description, 60) }}</td>
                                    <td>{{ $config->updated_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" onclick="editConfig({{ $config->id }}, '{{ $config->key }}', '{{ addslashes($config->value) }}', '{{ $config->group }}', '{{ addslashes($config->description) }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.configurations.destroy', $config) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this configuration?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No configurations found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($configurations->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $configurations->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-tools me-1"></i>
                    System Actions
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('admin.configurations.initialize') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100" onclick="return confirm('This will initialize default configurations. Continue?')">
                                <i class="fas fa-sync me-1"></i> Initialize Defaults
                            </button>
                        </form>
                        
                        <a href="{{ route('admin.configurations.export') }}" class="btn btn-success w-100">
                            <i class="fas fa-download me-1"></i> Export Configurations
                        </a>
                        
                        <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fas fa-upload me-1"></i> Import Configurations
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Configuration Guidelines
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check-circle text-success me-2"></i> Use descriptive keys</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Group related settings</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Always provide descriptions</li>
                        <li><i class="fas fa-exclamation-triangle text-warning me-2"></i> Backup before changes</li>
                        <li><i class="fas fa-exclamation-triangle text-warning me-2"></i> Test after modifications</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Configuration Modal -->
<div class="modal fade" id="addConfigModal" tabindex="-1" aria-labelledby="addConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.configurations.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addConfigModalLabel">Add Configuration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="key" class="form-label">Key</label>
                        <input type="text" class="form-control" id="key" name="key" required>
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <textarea class="form-control" id="value" name="value" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="group" class="form-label">Group</label>
                        <select class="form-select" id="group" name="group">
                            <option value="">Select Group</option>
                            <option value="app">Application</option>
                            <option value="mail">Mail</option>
                            <option value="cache">Cache</option>
                            <option value="session">Session</option>
                            <option value="database">Database</option>
                            <option value="queue">Queue</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Configuration Modal -->
<div class="modal fade" id="editConfigModal" tabindex="-1" aria-labelledby="editConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.configurations.update') }}" id="editConfigForm">
                @csrf
                <input type="hidden" id="edit_config_id" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editConfigModalLabel">Edit Configuration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_key" class="form-label">Key</label>
                        <input type="text" class="form-control" id="edit_key" name="key" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_value" class="form-label">Value</label>
                        <textarea class="form-control" id="edit_value" name="value" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_group" class="form-label">Group</label>
                        <select class="form-select" id="edit_group" name="group">
                            <option value="">Select Group</option>
                            <option value="app">Application</option>
                            <option value="mail">Mail</option>
                            <option value="cache">Cache</option>
                            <option value="session">Session</option>
                            <option value="database">Database</option>
                            <option value="queue">Queue</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Configuration Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.configurations.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Configurations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="import_file" class="form-label">Configuration File</label>
                        <input type="file" class="form-control" id="import_file" name="file" accept=".json,.csv" required>
                        <div class="form-text">Supported formats: JSON, CSV</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="overwrite" name="overwrite" value="1">
                            <label class="form-check-label" for="overwrite">
                                Overwrite existing configurations
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Import Configurations</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editConfig(id, key, value, group, description) {
    document.getElementById('edit_config_id').value = id;
    document.getElementById('edit_key').value = key;
    document.getElementById('edit_value').value = value;
    document.getElementById('edit_group').value = group;
    document.getElementById('edit_description').value = description;
    
    const editModal = new bootstrap.Modal(document.getElementById('editConfigModal'));
    editModal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchConfig');
    const filterGroup = document.getElementById('filterGroup');
    const table = document.getElementById('configurationsTable');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const groupFilter = filterGroup.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const key = row.cells[0].textContent.toLowerCase();
            const value = row.cells[1].textContent.toLowerCase();
            const group = row.cells[2].textContent.toLowerCase();
            const description = row.cells[3].textContent.toLowerCase();
            
            const matchesSearch = key.includes(searchTerm) || 
                                value.includes(searchTerm) || 
                                description.includes(searchTerm);
            const matchesGroup = !groupFilter || group.includes(groupFilter);
            
            row.style.display = matchesSearch && matchesGroup ? '' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterTable);
    filterGroup.addEventListener('change', filterTable);
});
</script>
@endpush
@endsection
