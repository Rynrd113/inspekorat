@props([
    'name' => '',
    'options' => [],
    'value' => '',
    'placeholder' => 'Pilih...',
    'label' => '',
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'error' => null,
    'help' => null,
    'size' => 'md',
    'searchable' => false
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

$selectClasses = collect([
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
    
    <select 
        id="{{ $inputId }}"
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        class="{{ $selectClasses }}"
        {{ $attributes->except(['class', 'name', 'required', 'disabled', 'multiple']) }}
    >
        @if(!$multiple && $placeholder)
        <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $optionValue => $optionLabel)
            @if(is_array($optionLabel))
            <optgroup label="{{ $optionValue }}">
                @foreach($optionLabel as $subValue => $subLabel)
                <option value="{{ $subValue }}" {{ (old($name, $value) == $subValue) ? 'selected' : '' }}>
                    {{ $subLabel }}
                </option>
                @endforeach
            </optgroup>
            @else
            <option value="{{ $optionValue }}" {{ (old($name, $value) == $optionValue) ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
            @endif
        @endforeach
    </select>
    
    @if($help)
    <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
    
    @if($hasError)
    <p class="text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div>
