// Test the JavaScript syntax from the blade file
// Extract the main JavaScript functionality to check for syntax errors

class HeroSlider {
    constructor() {
        this.currentSlide = 0;
        this.slides = document.querySelectorAll('.slide');
        this.totalSlides = this.slides.length;
        this.prevBtn = document.querySelector('.prev-btn');
        this.nextBtn = document.querySelector('.next-btn');
        this.dots = document.querySelectorAll('.slider-dot');
        this.autoPlayTimer = null;
        this.isAutoPlaying = true;
        
        if (this.totalSlides === 0) {
            console.error('HeroSlider: No slides found');
            return;
        }
        
        if (!this.prevBtn || !this.nextBtn) {
            console.error('HeroSlider: Navigation buttons not found');
            return;
        }
        
        this.init();
    }
    
    init() {
        this.prevBtn.addEventListener('click', () => {
            this.prevSlide();
        });
        
        this.nextBtn.addEventListener('click', () => {
            this.nextSlide();
        });
        
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.goToSlide(index);
            });
        });
        
        const sliderContainer = document.querySelector('.hero-slider');
        sliderContainer.addEventListener('mouseenter', () => this.stopAutoPlay());
        sliderContainer.addEventListener('mouseleave', () => this.startAutoPlay());
        
        this.startAutoPlay();
    }
    
    goToSlide(index) {
        if (index < 0 || index >= this.totalSlides) {
            console.error('Invalid slide index or no slides found:', index);
            return;
        }
        
        this.slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        
        this.dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
        
        this.currentSlide = index;
    }
    
    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.totalSlides;
        this.goToSlide(nextIndex);
    }
    
    prevSlide() {
        const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        this.goToSlide(prevIndex);
    }
    
    startAutoPlay() {
        if (this.isAutoPlaying) {
            this.autoPlayTimer = setInterval(() => {
                this.nextSlide();
            }, 5000);
        }
    }
    
    stopAutoPlay() {
        if (this.autoPlayTimer) {
            clearInterval(this.autoPlayTimer);
            this.autoPlayTimer = null;
        }
    }
}

class BeritaFilter {
    constructor() {
        this.beritaData = []; // Mock data
        this.currentFilter = 'terbaru';
        this.beritaContainer = document.querySelector('#berita-list');
        this.btnTerbaru = document.querySelector('#btn-terbaru');
        this.btnTerpopuler = document.querySelector('#btn-terpopuler');
        
        if (!this.beritaContainer || !this.btnTerbaru || !this.btnTerpopuler) {
            console.error('BeritaFilter: Required DOM elements not found');
            return;
        }
        
        this.init();
    }
    
    init() {
        console.log('Berita data loaded:', this.beritaData.length, 'items');
        
        this.renderBerita(this.getFilteredData('terbaru'));
        this.updateButtonStates();
        
        this.btnTerbaru.addEventListener('click', () => {
            console.log('Filter: Terbaru clicked');
            this.currentFilter = 'terbaru';
            this.renderBerita(this.getFilteredData('terbaru'));
            this.updateButtonStates();
        });
        
        this.btnTerpopuler.addEventListener('click', () => {
            console.log('Filter: Terpopuler clicked');
            this.currentFilter = 'terpopuler';
            this.renderBerita(this.getFilteredData('terpopuler'));
            this.updateButtonStates();
        });
    }
    
    showLoading() {
        this.beritaContainer.innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-gray-600">Memuat berita...</span>
            </div>
        `;
    }
    
    updateButtonStates() {
        this.btnTerbaru.className = 'px-6 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-all duration-200';
        this.btnTerpopuler.className = 'px-6 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-all duration-200';
        
        if (this.currentFilter === 'terbaru') {
            this.btnTerbaru.className = 'px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md transition-all duration-200 hover:bg-blue-700';
            this.btnTerbaru.innerHTML = '<i class="fas fa-clock mr-2"></i>TERBARU';
        } else {
            this.btnTerpopuler.className = 'px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md transition-all duration-200 hover:bg-blue-700';
            this.btnTerpopuler.innerHTML = '<i class="fas fa-fire mr-2"></i>TERPOPULER';
        }
        
        if (this.currentFilter === 'terbaru') {
            this.btnTerpopuler.innerHTML = '<i class="fas fa-fire mr-2"></i>TERPOPULER';
        } else {
            this.btnTerbaru.innerHTML = '<i class="fas fa-clock mr-2"></i>TERBARU';
        }
    }
    
    getFilteredData(filter) {
        let sortedData = [...this.beritaData];
        
        if (filter === 'terbaru') {
            sortedData.sort((a, b) => new Date(b.published_at) - new Date(a.published_at));
        } else if (filter === 'terpopuler') {
            sortedData.sort((a, b) => (b.views || 0) - (a.views || 0));
        }
        
        console.log(`Filtered data (${filter}):`, sortedData.map(item => ({
            judul: item.judul,
            tanggal: item.published_at,
            views: item.views
        })));
        
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

console.log('JavaScript syntax test completed successfully!');
