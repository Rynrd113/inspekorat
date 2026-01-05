<!-- Hero Slider Section - Simple Photo Slider -->
<section id="beranda" class="relative overflow-hidden">
    <div class="hero-slider relative h-[50vh] min-h-[400px] sm:h-[60vh] sm:min-h-[500px] lg:h-[70vh] max-h-[700px] w-full">
        @if($heroSliders->count() > 0)
            @foreach($heroSliders as $index => $slider)
            <!-- Slide {{ $index + 1 }} -->
            <div class="slide absolute inset-0 w-full h-full transition-opacity duration-700 {{ $index === 0 ? 'active opacity-100' : 'opacity-0' }}">
                @if($slider->gambar_url)
                    <!-- Background Image -->
                    <div class="absolute inset-0">
                        <img src="{{ $slider->gambar_url }}" 
                             alt="{{ $slider->judul ?? 'Slider ' . ($index + 1) }}" 
                             class="w-full h-full object-cover"
                             loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                    </div>
                @else
                    <!-- Fallback Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800"></div>
                @endif
                
                <!-- Optional: Title/Caption overlay (only if title exists) -->
                @if($slider->judul)
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent p-4 sm:p-6 lg:p-8">
                    <div class="max-w-7xl mx-auto">
                        <h2 class="text-white text-lg sm:text-xl lg:text-2xl font-semibold">
                            {{ $slider->judul }}
                        </h2>
                        @if($slider->subjudul)
                        <p class="text-white/80 text-sm sm:text-base mt-1">
                            {{ $slider->subjudul }}
                        </p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @endforeach

            <!-- Slider Navigation Dots -->
            @if($heroSliders->count() > 1)
            <div class="absolute bottom-4 sm:bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2 sm:space-x-3 z-20">
                @foreach($heroSliders as $index => $slider)
                <button class="slider-dot w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-white/50 hover:bg-white transition-all duration-300 shadow-lg {{ $index === 0 ? 'active bg-white' : '' }}" 
                        data-slide="{{ $index }}"
                        aria-label="Go to slide {{ $index + 1 }}"></button>
                @endforeach
            </div>

            <!-- Slider Arrows -->
            <button class="absolute left-2 sm:left-4 top-1/2 transform -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 bg-black/30 hover:bg-black/50 rounded-full flex items-center justify-center text-white transition-all duration-300 z-20" 
                    id="prevSlide"
                    aria-label="Previous slide">
                <i class="fas fa-chevron-left text-sm sm:text-base"></i>
            </button>
            <button class="absolute right-2 sm:right-4 top-1/2 transform -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 bg-black/30 hover:bg-black/50 rounded-full flex items-center justify-center text-white transition-all duration-300 z-20" 
                    id="nextSlide"
                    aria-label="Next slide">
                <i class="fas fa-chevron-right text-sm sm:text-base"></i>
            </button>
            @endif
        @else
            <!-- Fallback Static Slide if no sliders in database -->
            <div class="slide absolute inset-0 w-full h-full active">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800"></div>
                <div class="relative h-full flex items-center justify-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center text-white">
                            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                                Portal Inspektorat<br>Provinsi Papua Tengah
                            </h1>
                            <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto">
                                Pengawasan yang Akuntabel & Transparan
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<style>
.hero-slider .slide {
    transition: opacity 0.7s ease-in-out;
}
.hero-slider .slide.active {
    opacity: 1;
    z-index: 10;
}
.hero-slider .slide:not(.active) {
    opacity: 0;
    z-index: 1;
}
.slider-dot.active {
    background-color: white;
    transform: scale(1.2);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero-slider .slide');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');
    
    if (slides.length <= 1) return;
    
    let currentSlide = 0;
    let autoSlideInterval;
    
    function showSlide(index) {
        // Handle wrap around
        if (index >= slides.length) index = 0;
        if (index < 0) index = slides.length - 1;
        
        // Update slides
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
            }
        });
        
        // Update dots
        dots.forEach((dot, i) => {
            dot.classList.remove('active', 'bg-white');
            dot.classList.add('bg-white/50');
            if (i === index) {
                dot.classList.add('active', 'bg-white');
                dot.classList.remove('bg-white/50');
            }
        });
        
        currentSlide = index;
    }
    
    function nextSlide() {
        showSlide(currentSlide + 1);
    }
    
    function prevSlide() {
        showSlide(currentSlide - 1);
    }
    
    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 5000);
    }
    
    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }
    
    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            stopAutoSlide();
            nextSlide();
            startAutoSlide();
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            stopAutoSlide();
            prevSlide();
            startAutoSlide();
        });
    }
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', function() {
            stopAutoSlide();
            showSlide(index);
            startAutoSlide();
        });
    });
    
    // Start auto slide
    startAutoSlide();
    
    // Pause on hover
    const sliderContainer = document.querySelector('.hero-slider');
    if (sliderContainer) {
        sliderContainer.addEventListener('mouseenter', stopAutoSlide);
        sliderContainer.addEventListener('mouseleave', startAutoSlide);
    }
});
</script>
@endpush
