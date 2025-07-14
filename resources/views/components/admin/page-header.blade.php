@props([
    'title' => '',
    'breadcrumbs' => [],
    'showActions' => true
])

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $title }}</h1>
                
                @if(!empty($breadcrumbs))
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-home mr-1"></i>Dashboard
                            </a>
                        </li>
                        @foreach($breadcrumbs as $breadcrumb)
                        <li class="flex items-center">
                            <i class="fas fa-chevron-right mx-2 text-gray-300"></i>
                            @if(isset($breadcrumb['url']) && !$loop->last)
                            <a href="{{ $breadcrumb['url'] }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                {{ $breadcrumb['label'] }}
                            </a>
                            @else
                            <span class="text-gray-600">{{ $breadcrumb['label'] ?? $breadcrumb }}</span>
                            @endif
                        </li>
                        @endforeach
                    </ol>
                </nav>
                @endif
            </div>
            
            @if($showActions && isset($actions))
            <div class="flex items-center space-x-3">
                {{ $actions }}
            </div>
            @endif
        </div>
    </div>
    
    {{ $slot }}
</div>