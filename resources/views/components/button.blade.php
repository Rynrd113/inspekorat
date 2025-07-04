@php
$baseClasses = 'inline-flex items-center justify-center rounded-lg font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none';

$variants = [
    'primary' => 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 focus:ring-blue-500',
    'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200 focus:ring-gray-500',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
    'outline' => 'border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white focus:ring-blue-500',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
    'xl' => 'px-8 py-4 text-lg',
];

$classes = collect([$baseClasses, $variants[$variant] ?? $variants['primary'], $sizes[$size] ?? $sizes['md']])
    ->filter()
    ->implode(' ');
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $classes }} {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" class="{{ $classes }} {{ $attributes->get('class') }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->except('class') }}>
        {{ $slot }}
    </button>
@endif