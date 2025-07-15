@props([
    'title' => '',
    'breadcrumbs' => [],
    'showActions' => false,
    'sticky' => false
])

<div class="bg-white {{ $sticky ? 'sticky top-0 z-10' : '' }} border-b border-gray-200">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div class="min-w-0 flex-1">
                <!-- Title -->
                @if($title)
                    <h1 class="text-2xl font-bold text-gray-900 truncate">{{ $title }}</h1>
                @endif
                
                <!-- Breadcrumbs -->
                @if(count($breadcrumbs) > 0)
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
                                @if(is_array($breadcrumb))
                                    @if(isset($breadcrumb['url']) && !$loop->last)
                                    <a href="{{ $breadcrumb['url'] }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                        {{ $breadcrumb['label'] }}
                                    </a>
                                    @else
                                    <span class="text-gray-600">{{ $breadcrumb['label'] ?? $breadcrumb }}</span>
                                    @endif
                                @else
                                    <span class="text-gray-600">{{ $breadcrumb }}</span>
                                @endif
                            </li>
                            @endforeach
                        </ol>
                    </nav>
                @endif
            </div>
            
            <!-- Actions -->
            @if($showActions && isset($actions))
            <div class="flex items-center space-x-3 ml-4">
                {{ $actions }}
            </div>
            @endif
        </div>
        
        <!-- Slot for additional content -->
        {{ $slot }}
    </div>
</div>