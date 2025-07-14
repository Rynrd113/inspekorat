@props([
    'size' => 'md',
    'placeholder' => 'Cari...',
    'withIcon' => false,
    'icon' => 'fas fa-search',
    'clearButton' => false,
    'containerClass' => ''
])

@php
$baseClasses = 'w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200';

$sizes = [
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-3 text-base',
    'lg' => 'px-5 py-4 text-lg',
];

$sizeClasses = $sizes[$size] ?? $sizes['md'];

$classes = collect([$baseClasses, $sizeClasses])
    ->filter()
    ->implode(' ');
@endphp

<div class="relative {{ $containerClass ?? '' }}">
    <input 
        type="text" 
        class="{{ $classes }} {{ $withIcon ? 'pl-10' : '' }} {{ $attributes->get('class') }}"
        placeholder="{{ $placeholder ?? 'Cari...' }}"
        {{ $attributes->except(['class', 'placeholder']) }}
    >
    
    @if($withIcon)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="{{ $icon ?? 'fas fa-search' }} text-gray-400"></i>
        </div>
    @endif
    
    @if(isset($clearButton) && $clearButton)
        <button 
            type="button" 
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
            onclick="this.parentElement.querySelector('input').value = ''; this.parentElement.querySelector('input').dispatchEvent(new Event('input'));"
        >
            <i class="fas fa-times"></i>
        </button>
    @endif
</div>