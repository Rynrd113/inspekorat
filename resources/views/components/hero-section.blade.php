@props([
    'title' => 'Hero Title',
    'subtitle' => '',
    'description' => '',
    'icon' => 'fas fa-home',
    'backgroundClass' => 'bg-gradient-to-br from-blue-800 via-blue-900 to-indigo-900'
])

<!-- Hero Section -->
<section class="{{ $backgroundClass }} text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            @if($icon)
            <div class="mb-8">
                <i class="{{ $icon }} text-6xl text-blue-200 mb-4"></i>
            </div>
            @endif
            
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $title }}</h1>
            
            @if($subtitle)
            <h2 class="text-2xl md:text-3xl font-medium text-blue-100 mb-6">{{ $subtitle }}</h2>
            @endif
            
            @if($description)
            <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                {{ $description }}
            </p>
            @endif
            
            {{ $slot }}
        </div>
    </div>
</section>