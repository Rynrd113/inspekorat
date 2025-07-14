@props([
    'items' => []
])

<!-- Breadcrumb -->
<nav class="bg-white border-b border-gray-200 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('public.index') }}" 
                   class="text-blue-600 hover:text-blue-700 transition-colors">
                    <i class="fas fa-home mr-1"></i>Beranda
                </a>
            </li>
            
            @foreach($items as $item)
            <li class="flex items-center">
                <span class="text-gray-400 mx-2">/</span>
                
                @if(isset($item['url']) && !$loop->last)
                <a href="{{ $item['url'] }}" 
                   class="text-blue-600 hover:text-blue-700 transition-colors">
                    {{ $item['label'] }}
                </a>
                @else
                <span class="text-gray-600">{{ $item['label'] ?? $item }}</span>
                @endif
            </li>
            @endforeach
        </ol>
    </div>
</nav>