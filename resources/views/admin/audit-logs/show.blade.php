@extends('layouts.admin')

@section('title', 'Audit Log Details')

@section('header', 'Audit Log Details')
@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><a href="{{ route('admin.audit-logs.index') }}" class="text-blue-600 hover:text-blue-800">Audit Logs</a></li>
<li><span class="text-gray-500">Log #{{ $auditLog->id }}</span></li>
@endsection

@section('main-content')
<x-card>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Log Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Log ID</p>
                <p class="font-medium">{{ $auditLog->id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">User</p>
                <p class="font-medium">
                    @if($auditLog->user)
                        {{ $auditLog->user->name }} ({{ $auditLog->user->email }})
                    @else
                        System
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Action</p>
                <p class="font-medium">
                    @switch($auditLog->action)
                        @case('created')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Created</span>
                            @break
                        @case('updated')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Updated</span>
                            @break
                        @case('deleted')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Deleted</span>
                            @break
                        @default
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($auditLog->action) }}</span>
                    @endswitch
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Model</p>
                <p class="font-medium">
                    <code>{{ $auditLog->model_type ? class_basename($auditLog->model_type) : 'N/A' }}</code>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Record ID</p>
                <p class="font-medium">{{ $auditLog->model_id ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">IP Address</p>
                <p class="font-medium">{{ $auditLog->ip_address }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Date</p>
                <p class="font-medium">{{ $auditLog->created_at->format('d M Y, H:i:s') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">User Agent</p>
                <p class="font-medium text-xs">{{ Str::limit($auditLog->user_agent, 60) }}</p>
            </div>
        </div>
        
        @if($auditLog->description)
        <div class="mt-4">
            <p class="text-sm text-gray-600">Description</p>
            <p class="font-medium">{{ $auditLog->description }}</p>
        </div>
        @endif
    </div>

    @if($auditLog->old_values || $auditLog->new_values)
    <div class="border-t pt-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Changes</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($auditLog->old_values)
            <div>
                <h4 class="text-sm font-medium text-red-600 mb-2">Old Values</h4>
                <pre class="bg-red-50 p-3 rounded-md text-sm overflow-x-auto">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
            </div>
            @endif
            
            @if($auditLog->new_values)
            <div>
                <h4 class="text-sm font-medium text-green-600 mb-2">New Values</h4>
                <pre class="bg-green-50 p-3 rounded-md text-sm overflow-x-auto">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
            </div>
            @endif
        </div>
    </div>
    @endif
</x-card>
@endsection
<div class="container-fluid px-4">
    <h1 class="mt-4">Audit Log Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.audit-logs.index') }}">Audit Logs</a></li>
        <li class="breadcrumb-item active">Log #{{ $auditLog->id }}</li>
    </ol>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Log Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Log ID:</strong>
                                <p class="mt-1">{{ $auditLog->id }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong>User:</strong>
                                <p class="mt-1">
                                    @if($auditLog->user)
                                        <span class="badge bg-info">{{ $auditLog->user->name }}</span>
                                        <br>
                                        <small class="text-muted">{{ $auditLog->user->email }}</small>
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Action:</strong>
                                <p class="mt-1">
                                    @switch($auditLog->action)
                                        @case('created')
                                            <span class="badge bg-success">Created</span>
                                            @break
                                        @case('updated')
                                            <span class="badge bg-warning">Updated</span>
                                            @break
                                        @case('deleted')
                                            <span class="badge bg-danger">Deleted</span>
                                            @break
                                        @case('viewed')
                                            <span class="badge bg-info">Viewed</span>
                                            @break
                                        @case('login')
                                            <span class="badge bg-primary">Login</span>
                                            @break
                                        @case('logout')
                                            <span class="badge bg-secondary">Logout</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">Approved</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                            @break
                                        @default
                                            <span class="badge bg-light text-dark">{{ ucfirst($auditLog->action) }}</span>
                                    @endswitch
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Model:</strong>
                                <p class="mt-1">
                                    <code>{{ $auditLog->model_type ? class_basename($auditLog->model_type) : 'N/A' }}</code>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Record ID:</strong>
                                <p class="mt-1">{{ $auditLog->model_id ?? 'N/A' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Timestamp:</strong>
                                <p class="mt-1">{{ $auditLog->created_at->format('d M Y, H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($auditLog->description)
                    <div class="mt-3">
                        <strong>Description:</strong>
                        <p class="mt-1 text-muted">{{ $auditLog->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            @if($auditLog->old_values || $auditLog->new_values)
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-exchange-alt me-1"></i>
                    Data Changes
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($auditLog->old_values)
                        <div class="col-md-6">
                            <h6 class="text-danger">Old Values:</h6>
                            <pre class="bg-light p-3 rounded"><code>{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                        @endif
                        
                        @if($auditLog->new_values)
                        <div class="col-md-6">
                            <h6 class="text-success">New Values:</h6>
                            <pre class="bg-light p-3 rounded"><code>{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                        @endif
                    </div>
                    
                    @if($auditLog->old_values && $auditLog->new_values)
                    <div class="mt-3">
                        <h6>Changed Fields:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Old Value</th>
                                        <th>New Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($auditLog->old_values as $key => $oldValue)
                                        @if(isset($auditLog->new_values[$key]) && $auditLog->new_values[$key] !== $oldValue)
                                        <tr>
                                            <td><strong>{{ $key }}</strong></td>
                                            <td class="text-danger">{{ $oldValue }}</td>
                                            <td class="text-success">{{ $auditLog->new_values[$key] }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    
                                    @foreach($auditLog->new_values as $key => $newValue)
                                        @if(!isset($auditLog->old_values[$key]))
                                        <tr>
                                            <td><strong>{{ $key }}</strong></td>
                                            <td class="text-muted">-</td>
                                            <td class="text-success">{{ $newValue }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-desktop me-1"></i>
                    Session Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>IP Address:</strong>
                        <p class="mt-1">
                            <code>{{ $auditLog->ip_address }}</code>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>User Agent:</strong>
                        <p class="mt-1">
                            <small class="text-muted">{{ $auditLog->user_agent }}</small>
                        </p>
                    </div>
                    
                    @if($auditLog->session_id)
                    <div class="mb-3">
                        <strong>Session ID:</strong>
                        <p class="mt-1">
                            <code>{{ Str::limit($auditLog->session_id, 20) }}</code>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-history me-1"></i>
                    Related Logs
                </div>
                <div class="card-body">
                    @if($relatedLogs && $relatedLogs->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($relatedLogs as $relatedLog)
                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">
                                        @switch($relatedLog->action)
                                            @case('created')
                                                <span class="badge bg-success">Created</span>
                                                @break
                                            @case('updated')
                                                <span class="badge bg-warning">Updated</span>
                                                @break
                                            @case('deleted')
                                                <span class="badge bg-danger">Deleted</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ ucfirst($relatedLog->action) }}</span>
                                        @endswitch
                                    </div>
                                    <small class="text-muted">{{ $relatedLog->created_at->format('d M Y, H:i') }}</small>
                                </div>
                                <a href="{{ route('admin.audit-logs.show', $relatedLog) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No related logs found</p>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tools me-1"></i>
                    Actions
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Logs
                        </a>
                        
                        @if($auditLog->model_type && $auditLog->model_id)
                        <button class="btn btn-info" onclick="viewRelatedRecord()">
                            <i class="fas fa-external-link-alt me-1"></i> View Related Record
                        </button>
                        @endif
                        
                        <button class="btn btn-primary" onclick="exportLog()">
                            <i class="fas fa-download me-1"></i> Export Log
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewRelatedRecord() {
    const modelType = '{{ $auditLog->model_type }}';
    const modelId = '{{ $auditLog->model_id }}';
    
    // This would need to be implemented based on your routing structure
    // For now, just show an alert
    alert('View related record functionality would be implemented here');
}

function exportLog() {
    const logData = {
        id: {{ $auditLog->id }},
        user: '{{ $auditLog->user->name ?? 'System' }}',
        action: '{{ $auditLog->action }}',
        model: '{{ $auditLog->model_type }}',
        model_id: '{{ $auditLog->model_id }}',
        old_values: @json($auditLog->old_values),
        new_values: @json($auditLog->new_values),
        ip_address: '{{ $auditLog->ip_address }}',
        user_agent: '{{ $auditLog->user_agent }}',
        created_at: '{{ $auditLog->created_at->toISOString() }}'
    };
    
    const blob = new Blob([JSON.stringify(logData, null, 2)], {type: 'application/json'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `audit-log-${logData.id}.json`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}
</script>
@endpush
@endsection
