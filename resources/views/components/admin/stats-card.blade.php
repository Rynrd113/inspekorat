@props([
    'title' => '',
    'value' => '',
    'icon' => 'fas fa-chart-line',
    'color' => 'blue',
    'url' => null,
    'description' => null,
    'trend' => null,
    'trendIcon' => null,
    'trendColor' => 'green'
])

@php
$colors = [
    'blue' => 'from-blue-500 to-blue-600',
    'green' => 'from-green-500 to-green-600',
    'yellow' => 'from-yellow-500 to-yellow-600',
    'red' => 'from-red-500 to-red-600',
    'purple' => 'from-purple-500 to-purple-600',
    'indigo' => 'from-indigo-500 to-indigo-600',
    'pink' => 'from-pink-500 to-pink-600',
    'gray' => 'from-gray-500 to-gray-600',
];

$gradientClass = $colors[$color] ?? $colors['blue'];
@endphp

<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
    @if($url)
    <a href="{{ $url }}" class="block">
    @endif
    
    <div class="relative">
        <!-- Gradient Header -->
        <div class="bg-gradient-to-r {{ $gradientClass }} p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-white">{{ $title }}</h3>
                    <p class="text-3xl font-bold text-white mt-2">{{ $value }}</p>
                    
                    @if($description)
                    <p class="text-sm text-white/80 mt-1">{{ $description }}</p>
                    @endif
                </div>
                <div class="flex-shrink-0">
                    <i class="{{ $icon }} text-3xl text-white/80"></i>
                </div>
            </div>
        </div>
        
        @if($trend)
        <!-- Trend Indicator -->
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center">
                @if($trendIcon)
                <i class="{{ $trendIcon }} text-{{ $trendColor }}-500 mr-2"></i>
                @endif
                <span class="text-sm text-gray-600">{{ $trend }}</span>
            </div>
        </div>
        @endif
    </div>
    
    @if($url)
    </a>
    @endif
</div>