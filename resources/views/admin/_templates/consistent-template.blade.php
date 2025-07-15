@extends('layouts.admin')

@section('title', 'Page Title')

@section('header', 'Page Header')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Current Page</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Page Title</h1>
            <p class="text-gray-600 mt-1">Page description</p>
        </div>
        <div class="flex items-center space-x-3">
            <!-- Action buttons -->
            <a href="#" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Add New
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <!-- Your content here -->
    </div>
</div>
@endsection
