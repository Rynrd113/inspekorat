@props([
    'preload' => [],
    'critical' => [],
    'defer' => [],
    'modulePreload' => []
])

@php
$preloadAssets = $preload;
$criticalAssets = $critical;
$deferredAssets = $defer;
$modulePreloadAssets = $modulePreload;
@endphp

{{-- Critical CSS - load immediately --}}
@if($criticalAssets)
    @foreach($criticalAssets as $asset)
        @if(str_ends_with($asset, '.css'))
            <link rel="stylesheet" href="{{ $asset }}" />
        @elseif(str_ends_with($asset, '.js'))
            <script src="{{ $asset }}"></script>
        @endif
    @endforeach
@endif

{{-- Preload critical resources --}}
@if($preloadAssets)
    @foreach($preloadAssets as $asset => $type)
        @if($type === 'style')
            <link rel="preload" href="{{ $asset }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
            <noscript><link rel="stylesheet" href="{{ $asset }}"></noscript>
        @elseif($type === 'script')
            <link rel="preload" href="{{ $asset }}" as="script">
        @elseif($type === 'font')
            <link rel="preload" href="{{ $asset }}" as="font" type="font/woff2" crossorigin>
        @elseif($type === 'image')
            <link rel="preload" href="{{ $asset }}" as="image">
        @endif
    @endforeach
@endif

{{-- Module preload for modern browsers --}}
@if($modulePreloadAssets)
    @foreach($modulePreloadAssets as $asset)
        <link rel="modulepreload" href="{{ $asset }}">
    @endforeach
@endif

{{-- Deferred assets - load after page load --}}
@if($deferredAssets)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load deferred CSS
            @foreach($deferredAssets as $asset)
                @if(str_ends_with($asset, '.css'))
                    const link{{ $loop->index }} = document.createElement('link');
                    link{{ $loop->index }}.rel = 'stylesheet';
                    link{{ $loop->index }}.href = '{{ $asset }}';
                    document.head.appendChild(link{{ $loop->index }});
                @elseif(str_ends_with($asset, '.js'))
                    const script{{ $loop->index }} = document.createElement('script');
                    script{{ $loop->index }}.src = '{{ $asset }}';
                    script{{ $loop->index }}.defer = true;
                    document.head.appendChild(script{{ $loop->index }});
                @endif
            @endforeach
        });
    </script>
@endif

{{-- Resource hints for better performance --}}
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

{{-- Service Worker registration --}}
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('ServiceWorker registration successful');
            })
            .catch(function(error) {
                console.log('ServiceWorker registration failed');
            });
    });
}
</script>

{{-- Performance monitoring --}}
<script>
// Web Vitals monitoring
if (typeof PerformanceObserver !== 'undefined') {
    // Largest Contentful Paint
    new PerformanceObserver((entryList) => {
        for (const entry of entryList.getEntries()) {
            console.log('LCP:', entry.startTime);
        }
    }).observe({entryTypes: ['largest-contentful-paint']});

    // First Input Delay
    new PerformanceObserver((entryList) => {
        for (const entry of entryList.getEntries()) {
            console.log('FID:', entry.processingStart - entry.startTime);
        }
    }).observe({entryTypes: ['first-input']});

    // Cumulative Layout Shift
    new PerformanceObserver((entryList) => {
        for (const entry of entryList.getEntries()) {
            if (!entry.hadRecentInput) {
                console.log('CLS:', entry.value);
            }
        }
    }).observe({entryTypes: ['layout-shift']});
}
</script>
