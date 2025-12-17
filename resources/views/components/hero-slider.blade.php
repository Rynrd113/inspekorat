<!-- Hero Slider Section - Dynamic from Database -->
<section id="beranda" class="relative overflow-hidden">
    <div class="hero-slider relative h-[60vh] min-h-[500px] sm:h-[70vh] sm:min-h-[600px] lg:h-[85vh] max-h-[800px] w-full">
        @if($heroSliders->count() > 0)
            @foreach($heroSliders as $index => $slider)
            <!-- Slide {{ $index + 1 }} - {{ $slider->judul }} -->
            <div class="slide absolute inset-0 w-full h-full {{ $index === 0 ? 'active' : '' }}">
                @if($slider->gambar_url)
                    <!-- Background Image -->
                    <div class="absolute inset-0">
                        <img src="{{ $slider->gambar_url }}" alt="{{ $slider->judul }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-br from-black/60 via-black/40 to-black/60"></div>
                    </div>
                @else
                    <!-- Gradient Background based on category -->
                    @php
                        $gradients = [
                            'pengumuman' => 'from-purple-600 via-purple-700 to-indigo-800',
                            'event' => 'from-blue-600 via-blue-700 to-indigo-800',
                            'layanan' => 'from-emerald-600 via-teal-700 to-cyan-800',
                            'berita' => 'from-indigo-600 via-purple-700 to-pink-800',
                        ];
                        $gradient = $gradients[$slider->kategori] ?? 'from-blue-600 via-blue-700 to-indigo-800';
                    @endphp
                    <div class="absolute inset-0 bg-gradient-to-br {{ $gradient }}"></div>
                    <div class="absolute inset-0 bg-black/10"></div>
                @endif
                
                <!-- Geometric Pattern -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-10 right-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-20 left-20 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute top-1/2 left-1/3 w-32 h-32 bg-white/20 rounded-full blur-2xl"></div>
                </div>

                <!-- Content -->
                <div class="relative h-full flex items-center justify-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center text-white">
                            <!-- Category Badge -->
                            @if($slider->kategori)
                            <div class="mb-4 sm:mb-6">
                                <span class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                                    <i class="fas fa-{{ $slider->kategori == 'pengumuman' ? 'bullhorn' : ($slider->kategori == 'event' ? 'calendar-alt' : ($slider->kategori == 'layanan' ? 'concierge-bell' : 'newspaper')) }} mr-2"></i>
                                    {{ ucfirst($slider->kategori) }}
                                </span>
                            </div>
                            @endif

                            <!-- Title -->
                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4 sm:mb-6 leading-tight px-4">
                                {!! nl2br(e($slider->judul)) !!}
                            </h1>

                            <!-- Subtitle -->
                            @if($slider->subjudul)
                            <p class="text-lg sm:text-xl md:text-2xl lg:text-3xl text-white/90 mb-4 sm:mb-6 max-w-4xl mx-auto font-light px-4">
                                {{ $slider->subjudul }}
                            </p>
                            @endif

                            <!-- Description -->
                            @if($slider->deskripsi)
                            <p class="text-base sm:text-lg text-white/80 mb-6 sm:mb-8 max-w-3xl mx-auto px-4">
                                {{ $slider->deskripsi }}
                            </p>
                            @endif

                            <!-- CTA Buttons -->
                            @if($slider->link_url)
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4">
                                <a href="{{ $slider->link_url }}" 
                                   class="bg-white text-gray-900 px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-sm sm:text-base"
                                   onclick="trackSliderClick({{ $slider->id }})">
                                    {{ $slider->link_text ?? 'Selengkapnya' }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Slider Navigation Dots -->
            <div class="absolute bottom-4 sm:bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2 sm:space-x-3 z-20">
                @foreach($heroSliders as $index => $slider)
                <button class="slider-dot w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-white/50 hover:bg-white transition-all duration-300 shadow-lg {{ $index === 0 ? 'active' : '' }}" 
                        data-slide="{{ $index }}"></button>
                @endforeach
            </div>

            <!-- Slider Arrows -->
            <button class="absolute left-2 sm:left-4 lg:left-6 top-1/2 transform -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all duration-300 z-20 border border-white/20 hover:border-white/40" 
                    id="prevSlide">
                <i class="fas fa-chevron-left text-sm sm:text-base lg:text-lg"></i>
            </button>
            <button class="absolute right-2 sm:right-4 lg:right-6 top-1/2 transform -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all duration-300 z-20 border border-white/20 hover:border-white/40" 
                    id="nextSlide">
                <i class="fas fa-chevron-right text-sm sm:text-base lg:text-lg"></i>
            </button>
        @else
            <!-- Fallback Static Slide if no sliders in database -->
            <div class="slide absolute inset-0 w-full h-full active">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800"></div>
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative h-full flex items-center justify-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center text-white">
                            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold mb-6 leading-tight">
                                Portal Inspektorat<br>Provinsi Papua Tengah
                            </h1>
                            <p class="text-xl sm:text-2xl lg:text-3xl text-blue-100 mb-8 max-w-4xl mx-auto font-light">
                                Pengawasan yang Akuntabel & Transparan
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
// Track slider click for analytics
function trackSliderClick(sliderId) {
    fetch(`/api/hero-sliders/${sliderId}/view`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).catch(err => console.log('Analytics tracking failed'));
}

// Auto-increment views on slide change
let currentSlideId = null;
document.querySelectorAll('.slide').forEach((slide, index) => {
    if (slide.classList.contains('active')) {
        currentSlideId = {{ $heroSliders->first()->id ?? 'null' }};
    }
});
</script>
@endpush
