@props([
    'title' => '',
    'subtitle' => '',
    'icon' => null,
    'variant' => 'default',
    'showDivider' => true
])

@php
$variants = [
    'default' => 'bg-white border-gray-200',
    'primary' => 'bg-blue-50 border-blue-200',
    'success' => 'bg-green-50 border-green-200',
    'warning' => 'bg-yellow-50 border-yellow-200',
    'danger' => 'bg-red-50 border-red-200',
];

$cardClass = $variants[$variant] ?? $variants['default'];
@endphp

<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
    @if($title || $subtitle || $icon || isset($header))
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            @if(isset($header))
                {{ $header }}
            @else
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        @if($icon)
                            <i class="{{ $icon }} text-xl text-gray-600 mr-3"></i>
                        @endif
                        <div>
                            @if($title)
                                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                            @endif
                            @if($subtitle)
                                <p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
                            @endif
                        </div>
                    </div>
                    @isset($actions)
                        <div class="flex items-center space-x-2">
                            {{ $actions }}
                        </div>
                    @endisset
                </div>
            @endif
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
    
    @isset($footer)
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $footer }}
        </div>
    @endisset
</div>
