@props([
    'type' => 'spinner', // spinner, skeleton, dots
    'size' => 'md', // sm, md, lg
    'text' => 'Memuat data...',
    'rows' => 5
])

@php
$sizeClasses = [
    'sm' => 'h-4 w-4',
    'md' => 'h-6 w-6',
    'lg' => 'h-8 w-8'
];
@endphp

<div class="flex flex-col items-center justify-center py-8">
    @if($type === 'spinner')
        <div class="animate-spin rounded-full {{ $sizeClasses[$size] }} border-b-2 border-blue-600 mb-4"></div>
        <p class="text-sm text-gray-500">{{ $text }}</p>
    @elseif($type === 'dots')
        <div class="flex space-x-1 mb-4">
            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"></div>
            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
        </div>
        <p class="text-sm text-gray-500">{{ $text }}</p>
    @elseif($type === 'skeleton')
        <div class="w-full max-w-md space-y-3">
            @for($i = 0; $i < $rows; $i++)
                <div class="animate-pulse">
                    <div class="flex space-x-4">
                        <div class="rounded-full bg-gray-300 h-10 w-10"></div>
                        <div class="flex-1 space-y-2 py-1">
                            <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-300 rounded w-1/2"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    @endif
</div>