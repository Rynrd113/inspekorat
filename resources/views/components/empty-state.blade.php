@props([
    'title' => 'Tidak ada data ditemukan',
    'description' => 'Belum ada data yang tersedia saat ini',
    'icon' => 'fas fa-inbox',
    'action' => null,
    'actionText' => 'Tambah Data',
    'actionUrl' => '#',
    'actionVariant' => 'primary',
    'image' => null,
    'suggestion' => null
])

<div class="text-center py-12">
    @if($image)
        <img src="{{ $image }}" alt="Empty State" class="mx-auto h-32 w-32 mb-6 opacity-50">
    @else
        <div class="mx-auto h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center mb-6">
            <i class="{{ $icon }} text-gray-300 text-3xl"></i>
        </div>
    @endif
    
    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $title }}</h3>
    <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">{{ $description }}</p>
    
    @if($suggestion)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 max-w-md mx-auto">
            <div class="flex items-center">
                <i class="fas fa-lightbulb text-blue-600 mr-2"></i>
                <span class="text-sm text-blue-700">{{ $suggestion }}</span>
            </div>
        </div>
    @endif
    
    @if($action)
        <x-button 
            href="{{ $actionUrl }}" 
            variant="{{ $actionVariant }}" 
            size="md"
            class="inline-flex items-center"
        >
            <i class="fas fa-plus mr-2"></i>{{ $actionText }}
        </x-button>
    @endif
</div>