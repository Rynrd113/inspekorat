@props([
    'variant' => 'info', 
    'dismissible' => false,
    'title' => null,
    'icon' => null
])

@php
$variants = [
    'success' => [
        'container' => 'bg-green-50 border-green-200 text-green-800',
        'icon' => 'fas fa-check-circle text-green-600',
        'button' => 'text-green-500 hover:text-green-600'
    ],
    'error' => [
        'container' => 'bg-red-50 border-red-200 text-red-800',
        'icon' => 'fas fa-exclamation-triangle text-red-600',
        'button' => 'text-red-500 hover:text-red-600'
    ],
    'warning' => [
        'container' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'icon' => 'fas fa-exclamation-triangle text-yellow-600',
        'button' => 'text-yellow-500 hover:text-yellow-600'
    ],
    'info' => [
        'container' => 'bg-blue-50 border-blue-200 text-blue-800',
        'icon' => 'fas fa-info-circle text-blue-600',
        'button' => 'text-blue-500 hover:text-blue-600'
    ]
];

$config = $variants[$variant];
$iconClass = $icon ?? $config['icon'];
@endphp

<div class="border rounded-lg p-4 {{ $config['container'] }} {{ $attributes->get('class') }}">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="{{ $iconClass }} text-lg"></i>
        </div>
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="font-medium mb-1">{{ $title }}</h3>
            @endif
            <div class="text-sm">
                {{ $slot }}
            </div>
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="{{ $config['button'] }} hover:bg-opacity-20 rounded-md p-1">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        @endif
    </div>
</div>