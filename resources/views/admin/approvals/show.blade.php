@extends('layouts.admin')

@section('title', 'Content Approval Details')

@section('header', 'Content Approval Details')
@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><a href="{{ route('admin.approvals.index') }}" class="text-blue-600 hover:text-blue-800">Content Approvals</a></li>
<li><span class="text-gray-500">Details</span></li>
@endsection

@section('main-content')
<x-card>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Approval Details</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">ID</p>
                <p class="font-medium">{{ $approval->id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Type</p>
                <p class="font-medium">{{ $approval->model_type ? class_basename($approval->model_type) : 'Unknown' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Title</p>
                <p class="font-medium">{{ $approval->model ? ($approval->model->title ?? $approval->model->nama ?? 'No title') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <p class="font-medium">
                    @if($approval->status == 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @elseif($approval->status == 'approved')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Approved
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Rejected
                        </span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Created</p>
                <p class="font-medium">{{ $approval->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Author</p>
                <p class="font-medium">{{ $approval->submitter->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    @if($approval->status == 'pending')
    <div class="flex space-x-2">
        <form method="POST" action="{{ route('admin.approvals.approve', $approval) }}">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors" onclick="return confirm('Are you sure you want to approve this content?')">>
                <i class="fas fa-check mr-2"></i> Approve
            </button>
        </form>
        <form method="POST" action="{{ route('admin.approvals.reject', $approval) }}">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors" onclick="return confirm('Are you sure you want to reject this content?')">>
                <i class="fas fa-times mr-2"></i> Reject
            </button>
        </form>
    </div>
    @endif
</x-card>
@endsection
<div class="container-fluid px-4">
    <h1 class="mt-4">Content Approval Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.approvals.index') }}">Content Approvals</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-alt me-1"></i>
                            Content Details
                        </div>
                        <div>
                            <span class="badge bg-primary">{{ ucfirst($approval->content_type) }}</span>
                            @if($approval->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($approval->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($approval->status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Title:</strong>
                            <p class="mt-1">{{ $approval->title }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Author:</strong>
                            <p class="mt-1">{{ $approval->user->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    @if($approval->description)
                    <div class="mt-3">
                        <strong>Description:</strong>
                        <p class="mt-1">{{ $approval->description }}</p>
                    </div>
                    @endif
                    
                    @if($approval->content)
                    <div class="mt-3">
                        <strong>Content:</strong>
                        <div class="mt-1 border p-3 rounded">
                            {!! $approval->content !!}
                        </div>
                    </div>
                    @endif
                    
                    @if($approval->metadata)
                    <div class="mt-3">
                        <strong>Additional Information:</strong>
                        <pre class="bg-light p-2 rounded mt-1">{{ json_encode($approval->metadata, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Approval Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Submission Date:</strong>
                        <p class="mt-1">{{ $approval->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <p class="mt-1">{{ $approval->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                    
                    @if($approval->approved_by)
                    <div class="mb-3">
                        <strong>Approved By:</strong>
                        <p class="mt-1">{{ $approval->approvedBy->name ?? 'N/A' }}</p>
                    </div>
                    @endif
                    
                    @if($approval->approved_at)
                    <div class="mb-3">
                        <strong>Approval Date:</strong>
                        <p class="mt-1">{{ $approval->approved_at->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                    
                    @if($approval->rejection_reason)
                    <div class="mb-3">
                        <strong>Rejection Reason:</strong>
                        <p class="mt-1 text-danger">{{ $approval->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            @if($approval->status == 'pending')
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-cog me-1"></i>
                    Actions
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('admin.approvals.approve', $approval) }}">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Are you sure you want to approve this content?')">
                                <i class="fas fa-check me-1"></i> Approve Content
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times me-1"></i> Reject Content
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.approvals.reject', $approval) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required placeholder="Please provide a reason for rejecting this content..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Content</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
