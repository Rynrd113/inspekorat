@props([
    'title' => '',
    'description' => '',
    'icon' => null,
    'collapsible' => false,
    'collapsed' => false
])

<div class="admin-form-section">
    @if($title)
    <div class="admin-form-header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @if($icon)
                <i class="{{ $icon }} mr-3 text-blue-600"></i>
                @endif
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $title }}</h2>
                    @if($description)
                    <p class="text-sm text-gray-600 mt-1">{{ $description }}</p>
                    @endif
                </div>
            </div>
            
            @if($collapsible)
            <button 
                type="button"
                class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors"
                onclick="toggleSection(this)"
            >
                <i class="fas fa-chevron-{{ $collapsed ? 'down' : 'up' }}"></i>
            </button>
            @endif
        </div>
    </div>
    @endif
    
    <div class="admin-form-body {{ $collapsible && $collapsed ? 'hidden' : '' }}" data-section-content>
        {{ $slot }}
    </div>
</div>

@if($collapsible)
<script>
function toggleSection(button) {
    const content = button.closest('.bg-white').querySelector('[data-section-content]');
    const icon = button.querySelector('i');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        content.classList.add('hidden');
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}
</script>
@endif