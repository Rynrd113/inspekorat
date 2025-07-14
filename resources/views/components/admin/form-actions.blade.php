@props([
    'backUrl' => null,
    'backLabel' => 'Kembali',
    'submitLabel' => 'Simpan',
    'submitIcon' => 'fas fa-save',
    'showReset' => false,
    'resetLabel' => 'Reset',
    'extraActions' => []
])

<div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
    <div class="flex items-center space-x-3">
        @if($backUrl)
        <x-button 
            href="{{ $backUrl }}"
            variant="secondary" 
            size="md"
        >
            <i class="fas fa-arrow-left mr-2"></i>{{ $backLabel }}
        </x-button>
        @endif
        
        @if($showReset)
        <x-button 
            type="reset"
            variant="secondary" 
            size="md"
        >
            <i class="fas fa-undo mr-2"></i>{{ $resetLabel }}
        </x-button>
        @endif
    </div>
    
    <div class="flex items-center space-x-3">
        @foreach($extraActions as $action)
        <x-button 
            type="{{ $action['type'] ?? 'button' }}"
            variant="{{ $action['variant'] ?? 'secondary' }}"
            size="{{ $action['size'] ?? 'md' }}"
            {{ isset($action['onclick']) ? 'onclick="' . $action['onclick'] . '"' : '' }}
            {{ isset($action['href']) ? 'href="' . $action['href'] . '"' : '' }}
        >
            @if(isset($action['icon']))
            <i class="{{ $action['icon'] }} mr-2"></i>
            @endif
            {{ $action['label'] }}
        </x-button>
        @endforeach
        
        <x-button 
            type="submit" 
            variant="primary" 
            size="md"
        >
            <i class="{{ $submitIcon }} mr-2"></i>{{ $submitLabel }}
        </x-button>
    </div>
</div>