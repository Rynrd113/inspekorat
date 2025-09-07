@props(['size' => 'md', 'color' => 'default', 'showText' => false])

@php
$sizeClasses = [
    'sm' => 'w-6 h-6 text-sm',
    'md' => 'w-8 h-8 text-base',
    'lg' => 'w-10 h-10 text-lg',
    'xl' => 'w-12 h-12 text-xl'
];

$colorClasses = [
    'default' => 'text-gray-600 hover:text-gray-900',
    'white' => 'text-white hover:text-gray-200',
    'primary' => 'text-blue-600 hover:text-blue-800',
    'brand' => 'text-brand-primary hover:text-brand-secondary'
];

$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
$colorClass = $colorClasses[$color] ?? $colorClasses['default'];
@endphp

@if($links && count($links) > 0)
<div {{ $attributes->merge(['class' => 'flex items-center space-x-3']) }}>
    @foreach($links as $platform => $link)
        <a 
            href="{{ $link['url'] }}" 
            target="_blank" 
            rel="noopener noreferrer"
            class="inline-flex items-center justify-center {{ $sizeClass }} {{ $colorClass }} transition-colors duration-200 hover:scale-110 transform"
            title="{{ $link['name'] }}"
            aria-label="{{ $link['name'] }}"
        >
            <i class="{{ $link['icon'] }}"></i>
            @if($showText)
                <span class="ml-2 text-sm">{{ $link['name'] }}</span>
            @endif
        </a>
    @endforeach
</div>
@endif
