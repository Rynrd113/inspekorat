@props([
    'type' => 'text',
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'label' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left'
])

@php
$inputId = $name . '_' . uniqid();
$hasError = $error || $errors->has($name);
$errorMessage = $error ?? $errors->first($name);

$sizes = [
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-4 py-3 text-base',
];

$baseClasses = 'block w-full border rounded-md shadow-sm focus:ring-2 focus:ring-offset-0 transition-colors';
$sizeClasses = $sizes[$size] ?? $sizes['md'];

$inputClasses = collect([
    $baseClasses,
    $sizeClasses,
    $hasError ? 'border-red-300 focus:border-red-300 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500',
    $disabled ? 'bg-gray-50 cursor-not-allowed' : 'bg-white',
    $attributes->get('class')
])->filter()->implode(' ');
@endphp

<div class="space-y-1">
    @if($label)
    <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    @endif
    
    <div class="relative">
        @if($icon && $iconPosition === 'left')
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="{{ $icon }} text-gray-400"></i>
        </div>
        @endif
        
        @if($type === 'textarea')
        <textarea 
            id="{{ $inputId }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="{{ $inputClasses }} {{ $icon && $iconPosition === 'left' ? 'pl-10' : '' }} {{ $icon && $iconPosition === 'right' ? 'pr-10' : '' }}"
            {{ $attributes->except(['class', 'type', 'name', 'value', 'placeholder', 'required', 'disabled']) }}
        >{{ old($name, $value) }}</textarea>
        @else
        <input 
            type="{{ $type }}"
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="{{ $inputClasses }} {{ $icon && $iconPosition === 'left' ? 'pl-10' : '' }} {{ $icon && $iconPosition === 'right' ? 'pr-10' : '' }}"
            {{ $attributes->except(['class', 'type', 'name', 'value', 'placeholder', 'required', 'disabled']) }}
        />
        @endif
        
        @if($icon && $iconPosition === 'right')
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <i class="{{ $icon }} text-gray-400"></i>
        </div>
        @endif
    </div>
    
    @if($help)
    <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
    
    @if($hasError)
    <p class="text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div>
