@php
$inputClasses = 'block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors';
if ($error) {
    $inputClasses .= ' border-red-300 focus:border-red-500 focus:ring-red-500';
}
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $attributes->get('id', $attributes->get('name')) }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    @if($type === 'textarea')
        <textarea 
            class="{{ $inputClasses }} {{ $attributes->get('class') }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->except(['class', 'type']) }}
        >{{ $slot }}</textarea>
    @else
        <input 
            type="{{ $type }}"
            class="{{ $inputClasses }} {{ $attributes->get('class') }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->except(['class', 'type']) }}
        />
    @endif
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>