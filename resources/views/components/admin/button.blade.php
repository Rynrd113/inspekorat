@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'loadingText' => 'Loading...',
    'fullWidth' => false,
    'icon' => null,
    'iconPosition' => 'left'
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none';

$variants = [
    'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm',
    'secondary' => 'bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-500 shadow-sm',
    'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 shadow-sm',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-sm',
    'warning' => 'bg-yellow-600 text-white hover:bg-yellow-700 focus:ring-yellow-500 shadow-sm',
    'info' => 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500 shadow-sm',
    'outline' => 'border-2 border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-gray-500',
    'outline-primary' => 'border-2 border-blue-600 text-blue-600 hover:bg-blue-50 focus:ring-blue-500',
    'ghost' => 'text-gray-700 hover:bg-gray-100 focus:ring-gray-500',
    'link' => 'text-blue-600 hover:text-blue-800 hover:underline focus:ring-blue-500',
];

$sizes = [
    'xs' => 'px-2.5 py-1.5 text-xs rounded',
    'sm' => 'px-3 py-2 text-sm rounded-md',
    'md' => 'px-4 py-2 text-sm rounded-md',
    'lg' => 'px-4 py-2 text-base rounded-md',
    'xl' => 'px-6 py-3 text-base rounded-md',
];

$classes = collect([
    $baseClasses,
    $variants[$variant] ?? $variants['primary'],
    $sizes[$size] ?? $sizes['md'],
    $fullWidth ? 'w-full' : '',
    $attributes->get('class')
])->filter()->implode(' ');
@endphp

@if($href)
<a href="{{ $href }}" 
   class="{{ $classes }}" 
   {{ $attributes->except(['class', 'href']) }}>
    @if($icon && $iconPosition === 'left')
        <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
    @endif
    {{ $slot }}
    @if($icon && $iconPosition === 'right')
        <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'ml-2' }}"></i>
    @endif
</a>
@else
<button type="{{ $type }}" 
        class="{{ $classes }}" 
        {{ $disabled || $loading ? 'disabled' : '' }}
        {{ $attributes->except(['class', 'type']) }}>
    @if($loading)
        <i class="fas fa-spinner fa-spin {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
        {{ $loadingText }}
    @else
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'ml-2' }}"></i>
        @endif
    @endif
</button>
@endif
