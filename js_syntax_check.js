// Extract and validate the JavaScript from the Blade template
const beritaData = [
    {
        judul: "Test",
        konten: "Test content",
        kategori: "informasi",
        penulis: "Admin",
        published_at: "2024-01-15",
        views: 100
    }
];

// Global filter function
function filterBerita(filter) {
    console.log('Global filterBerita called with:', filter);
    if (typeof window.beritaFilterInstance !== 'undefined') {
        window.beritaFilterInstance.setFilter(filter);
    } else {
        console.warn('BeritaFilter instance not available');
    }
}

// Hero Slider Class
class HeroSlider {
    constructor() {
        this.slides = document.querySelectorAll('.hero-slider .slide');
        this.dots = document.querySelectorAll('.slider-dot');
        this.currentSlide = 0;
        this.autoSlideInterval = null;
        
        if (this.slides.length > 0) {
            this.init();
        }
    }
    
    init() {
        this.showSlide(0);
        this.startAutoSlide();
        this.bindEvents();
    }
    
    bindEvents() {
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });
    }
    
    startAutoSlide() {
        this.autoSlideInterval = setInterval(() => {
            this.nextSlide();
        }, 5000);
    }
    
    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        this.showSlide(this.currentSlide);
    }
    
    goToSlide(index) {
        clearInterval(this.autoSlideInterval);
        this.showSlide(index);
        this.startAutoSlide();
    }
    
    showSlide(index) {
        if (!this.slides.length || index < 0 || index >= this.slides.length) {
            console.error('Invalid slide index or no slides found:', index);
            return;
        }
        
        this.slides.forEach(slide => {
            slide.classList.remove('active');
            slide.style.opacity = '0';
            slide.style.transform = 'translateX(100%)';
        });
        
        this.dots.forEach(dot => dot.classList.remove('active'));
        
        if (this.slides[index]) {
            this.slides[index].classList.add('active');
            this.slides[index].style.opacity = '1';
            this.slides[index].style.transform = 'translateX(0)';
        }
        
        if (this.dots[index]) {
            this.dots[index].classList.add('active');
        }
        
        this.currentSlide = index;
    }
}

// Berita Filter Class
class BeritaFilter {
    constructor() {
        this.beritaData = beritaData;
        this.currentFilter = 'terbaru';
        this.beritaContainer = document.getElementById('berita-list');
        this.btnTerbaru = document.getElementById('btn-terbaru');
        this.btnTerpopuler = document.getElementById('btn-terpopuler');
        
        if (!this.beritaContainer) {
            console.error('BeritaFilter: Container not found');
            return;
        }
        
        if (!this.beritaData || this.beritaData.length === 0) {
            console.warn('BeritaFilter: No data available');
            this.beritaContainer.innerHTML = '<p class="text-center text-gray-500 py-8">Tidak ada berita tersedia</p>';
            return;
        }
        
        this.init();
    }
    
    init() {
        this.renderBerita(this.getFilteredData('terbaru'));
        this.updateButtonStates();
    }
    
    setFilter(filter) {
        this.currentFilter = filter;
        this.updateButtonStates();
        this.showLoading();
        
        setTimeout(() => {
            const filteredData = this.getFilteredData(filter);
            this.renderBerita(filteredData);
            this.hideLoading();
        }, 300);
    }
    
    updateButtonStates() {
        const btnTerbaru = document.getElementById('btn-terbaru');
        const btnTerpopuler = document.getElementById('btn-terpopuler');
        
        if (btnTerbaru && btnTerpopuler) {
            if (this.currentFilter === 'terbaru') {
                btnTerbaru.classList.add('bg-blue-600', 'text-white');
                btnTerbaru.classList.remove('bg-gray-100', 'text-gray-700');
                btnTerpopuler.classList.add('bg-gray-100', 'text-gray-700');
                btnTerpopuler.classList.remove('bg-blue-600', 'text-white');
            } else {
                btnTerpopuler.classList.add('bg-blue-600', 'text-white');
                btnTerpopuler.classList.remove('bg-gray-100', 'text-gray-700');
                btnTerbaru.classList.add('bg-gray-100', 'text-gray-700');
                btnTerbaru.classList.remove('bg-blue-600', 'text-white');
            }
        }
    }
    
    showLoading() {
        if (this.beritaContainer) {
            this.beritaContainer.innerHTML = '<p class="text-center text-gray-500 py-8">Memuat berita...</p>';
        }
    }
    
    hideLoading() {
        // Loading will be replaced by renderBerita
    }
    
    getFilteredData(filter) {
        let sortedData = [...this.beritaData];
        
        if (filter === 'terbaru') {
            sortedData.sort((a, b) => new Date(b.published_at) - new Date(a.published_at));
        } else if (filter === 'terpopuler') {
            sortedData.sort((a, b) => (b.views || 0) - (a.views || 0));
        }
        
        return sortedData;
    }
    
    renderBerita(data) {
        this.beritaContainer.innerHTML = '';
        
        data.forEach((portal, index) => {
            const beritaCard = this.createBeritaCard(portal, index, data.length);
            this.beritaContainer.appendChild(beritaCard);
        });
        
        this.beritaContainer.style.opacity = '0';
        setTimeout(() => {
            this.beritaContainer.style.opacity = '1';
        }, 100);
    }
    
    createBeritaCard(portal, index, total) {
        const card = document.createElement('div');
        card.className = 'bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden';
        
        const formatDate = (dateString) => {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        };
        
        const limitText = (text, limit) => {
            return text.length > limit ? text.substring(0, limit) + '...' : text;
        };
        
        const categoryClasses = index % 2 === 0 ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
        
        card.innerHTML = `
            <div class="flex flex-col lg:flex-row">
                <div class="flex-1 p-6 lg:p-8">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition-colors cursor-pointer">
                                ${portal.judul}
                            </h3>
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <i class="fas fa-calendar mr-2"></i>
                                <span>${formatDate(portal.published_at)}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-user mr-2"></i>
                                <span>${portal.penulis}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-eye mr-2"></i>
                                <span>${portal.views || 0} views</span>
                            </div>
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${categoryClasses}">
                                    ${portal.kategori.toUpperCase()}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-semibold text-gray-900">${index + 1} dari ${total}</span>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                        ${limitText(portal.konten, 200)}
                    </p>
                    
                    <div class="flex items-center justify-between">
                        <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors">
                            <span>Baca Selengkapnya</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <div class="lg:w-80 lg:flex-shrink-0">
                    <div class="h-64 lg:h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                        <i class="fas fa-image text-blue-400 text-4xl"></i>
                    </div>
                </div>
            </div>
        `;
        
        return card;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    
    // Initialize slider
    const slider = new HeroSlider();
    
    // Initialize filter
    const filter = new BeritaFilter();
    window.beritaFilterInstance = filter;
    
    // Add event listeners for filter buttons
    const btnTerbaru = document.getElementById('btn-terbaru');
    const btnTerpopuler = document.getElementById('btn-terpopuler');
    
    if (btnTerbaru) {
        btnTerbaru.addEventListener('click', function() {
            filterBerita('terbaru');
        });
    }
    
    if (btnTerpopuler) {
        btnTerpopuler.addEventListener('click', function() {
            filterBerita('terpopuler');
        });
    }
});

console.log('JavaScript syntax check completed successfully!');
