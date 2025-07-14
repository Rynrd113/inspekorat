@php
$variants = [
    'primary' => 'bg-blue-100 text-blue-800',
    'secondary' => 'bg-gray-100 text-gray-800',
    'success' => 'bg-green-100 text-green-800',
    'danger' => 'bg-red-100 text-red-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'info' => 'bg-blue-100 text-blue-800',
    'purple' => 'bg-purple-100 text-purple-800',
    'orange' => 'bg-orange-100 text-orange-800',
    'pink' => 'bg-pink-100 text-pink-800',
    'indigo' => 'bg-indigo-100 text-indigo-800',
    'teal' => 'bg-teal-100 text-teal-800',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-0.5 text-xs',
    'lg' => 'px-3 py-1 text-sm',
];

$baseClasses = 'inline-flex items-center font-medium rounded-full';
$variantClasses = $variants[$variant] ?? $variants['primary'];
$sizeClasses = $sizes[$size] ?? $sizes['md'];

$classes = collect([$baseClasses, $variantClasses, $sizeClasses])
    ->filter()
    ->implode(' ');
@endphp

<span class="{{ $classes }} {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
    @if(isset($icon))
        <i class="{{ $icon }} mr-1"></i>
    @endif
    {{ $slot }}
</span>