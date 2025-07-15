@props([
    'src',
    'alt' => '',
    'class' => '',
    'placeholder' => '/images/placeholder.svg',
    'width' => null,
    'height' => null,
    'loading' => 'lazy',
    'decoding' => 'async',
    'sizes' => null,
    'srcset' => null
])

<div class="lazy-image-container {{ $class }}" 
     data-src="{{ $src }}"
     data-alt="{{ $alt }}"
     style="@if($width) width: {{ $width }}px; @endif @if($height) height: {{ $height }}px; @endif">
    
    <!-- Placeholder while loading -->
    <div class="lazy-image-placeholder bg-gray-200 animate-pulse flex items-center justify-center"
         style="@if($width) width: {{ $width }}px; @endif @if($height) height: {{ $height }}px; @endif">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
    </div>
    
    <!-- Actual image (will be loaded when visible) -->
    <img class="lazy-image hidden transition-opacity duration-300"
         data-src="{{ $src }}"
         alt="{{ $alt }}"
         loading="{{ $loading }}"
         decoding="{{ $decoding }}"
         @if($sizes) sizes="{{ $sizes }}" @endif
         @if($srcset) srcset="{{ $srcset }}" @endif
         style="@if($width) width: {{ $width }}px; @endif @if($height) height: {{ $height }}px; @endif">
</div>

@pushOnce('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer untuk lazy loading
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const container = entry.target;
                const img = container.querySelector('.lazy-image');
                const placeholder = container.querySelector('.lazy-image-placeholder');
                
                if (img && placeholder) {
                    img.src = img.dataset.src;
                    img.alt = img.dataset.alt;
                    
                    img.onload = function() {
                        placeholder.classList.add('hidden');
                        img.classList.remove('hidden');
                        img.classList.add('opacity-100');
                        observer.unobserve(container);
                    };
                    
                    img.onerror = function() {
                        placeholder.innerHTML = '<span class="text-red-500 text-sm">Failed to load</span>';
                        observer.unobserve(container);
                    };
                }
            }
        });
    }, {
        rootMargin: '50px 0px',
        threshold: 0.01
    });

    // Observe all lazy image containers
    document.querySelectorAll('.lazy-image-container').forEach(container => {
        imageObserver.observe(container);
    });
});
</script>
@endPushOnce

@pushOnce('styles')
<style>
.lazy-image-container {
    position: relative;
    overflow: hidden;
}

.lazy-image-placeholder {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

.lazy-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.3s ease;
}
</style>
@endPushOnce
