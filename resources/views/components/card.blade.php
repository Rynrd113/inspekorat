<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200 ease-in-out {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
    @isset($header)
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            {{ $header }}
        </div>
    @endisset
    
    <div class="p-6">
        {{ $slot }}
    </div>
    
    @isset($footer)
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $footer }}
        </div>
    @endisset
</div>