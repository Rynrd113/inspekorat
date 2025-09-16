@props(['variant' => 'header', 'size' => 'md', 'showText' => true])

@php
$sizeClasses = [
    'sm' => 'h-8',
    'md' => 'h-12',
    'lg' => 'h-16',
    'xl' => 'h-20'
];

$textSizeClasses = [
    'sm' => 'text-sm',
    'md' => 'text-lg',
    'lg' => 'text-xl',
    'xl' => 'text-2xl'
];

$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
$textSizeClass = $textSizeClasses[$size] ?? $textSizeClasses['md'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center']) }}>
    <img 
        src="{{ $logoUrl }}" 
        alt="Logo {{ $siteName }}" 
        class="{{ $sizeClass }} w-auto object-contain"
        onerror="if(this.src!=='{{ asset('images/logo.png') }}'){this.src='{{ asset('images/logo.png') }}';} else {this.style.display='none';}"
    />
    
    @if($showText && ($variant === 'header' || $variant === 'footer'))
        <div class="ml-3 {{ $attributes->get('class', '') }}">
            <h1 class="{{ $textSizeClass }} font-bold leading-tight">Inspektorat</h1>
            <p class="text-sm opacity-75">Papua Tengah</p>
        </div>
    @endif
</div>
