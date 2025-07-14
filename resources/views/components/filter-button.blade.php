@php
$baseClasses = 'inline-flex items-center px-4 py-2 rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';

$variants = [
    'active' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
    'inactive' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-500',
];

$isActive = $active ?? false;
$variantClasses = $isActive ? $variants['active'] : $variants['inactive'];

$classes = collect([$baseClasses, $variantClasses])
    ->filter()
    ->implode(' ');
@endphp

<button 
    type="button" 
    class="{{ $classes }} {{ $attributes->get('class') }}" 
    data-filter="{{ $filter ?? '' }}"
    {{ $attributes->except(['class', 'filter']) }}
>
    @if(isset($icon))
        <i class="{{ $icon }} mr-2"></i>
    @endif
    {{ $slot }}
</button>