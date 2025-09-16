<!-- Navigation -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="{{ route('public.index') }}">
                        <x-site-logo variant="header" size="md" :show-text="true" />
                    </a>
                </div>
            </div>
            
            <nav class="hidden md:block">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('public.index') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium {{ request()->routeIs('public.index') ? 'text-blue-600' : '' }}">
                        Beranda
                    </a>
                    
                    <!-- Informasi Dropdown -->
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium flex items-center">
                            Informasi
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-1">
                                <a href="{{ route('public.profil') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                <a href="{{ route('public.berita.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Berita</a>
                                <a href="{{ route('public.galeri.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Galeri</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Layanan Dropdown -->
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium flex items-center">
                            Layanan
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-1">
                                <a href="{{ route('public.pelayanan.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pelayanan</a>
                                <a href="{{ route('public.dokumen.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dokumen</a>
                                <a href="{{ route('public.pengaduan') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengaduan</a>
                                <a href="{{ route('public.wbs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">WBS</a>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('public.faq') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium {{ request()->routeIs('public.faq') ? 'text-blue-600' : '' }}">
                        FAQ
                    </a>
                    <a href="{{ route('public.kontak') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium {{ request()->routeIs('public.kontak') ? 'text-blue-600' : '' }}">
                        Kontak
                    </a>
                    
                    <a href="{{ route('admin.login') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                        Admin
                    </a>
                </div>
            </nav>
            
            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" 
                        class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600" 
                        id="mobile-menu-button"
                        onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div class="md:hidden hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t border-gray-200">
            <a href="{{ route('public.index') }}" 
               class="block px-3 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('public.index') ? 'text-blue-600' : '' }}">
                Beranda
            </a>
            
            <!-- Informasi Section -->
            <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Informasi</div>
            <a href="{{ route('public.profil') }}" 
               class="block px-6 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('public.profil') ? 'text-blue-600' : '' }}">
                Profil
            </a>
            <a href="{{ route('public.berita.index') }}" 
               class="block px-6 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('public.berita.*') ? 'text-blue-600' : '' }}">
                Berita
            </a>
            <a href="{{ route('public.galeri.index') }}" 
               class="block px-6 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('public.galeri.*') ? 'text-blue-600' : '' }}">
                Galeri
            </a>
            
            <!-- Layanan Section -->
            <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Layanan</div>
            <a href="{{ route('public.pelayanan.index') }}" 
               class="block px-6 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('public.pelayanan.*') ? 'text-blue-600' : '' }}">
                Pelayanan
            </a>
            <a href="{{ route('public.dokumen.index') }}" 
               class="block px-6 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('public.dokumen.*') ? 'text-blue-600' : '' }}">
                Dokumen
            </a>
            <a href="{{ route('public.pengaduan') }}" 
               class="block px-6 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('public.pengaduan') ? 'text-blue-600' : '' }}">
                Pengaduan
            </a>
            <a href="{{ route('public.wbs') }}" 
               class="block px-6 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('public.wbs') ? 'text-blue-600' : '' }}">
                WBS
            </a>
            
            <!-- Other -->
            <a href="{{ route('public.faq') }}" 
               class="block px-3 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('public.faq') ? 'text-blue-600' : '' }}">
                FAQ
            </a>
            <a href="{{ route('public.kontak') }}" 
               class="block px-3 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('public.kontak') ? 'text-blue-600' : '' }}">
                Kontak
            </a>
            
            <a href="{{ route('admin.login') }}" 
               class="block px-3 py-2 text-blue-600 font-medium hover:bg-blue-50">
                Admin
            </a>
        </div>
    </div>
</header>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    const button = document.getElementById('mobile-menu-button');
    const icon = button.querySelector('i');
    
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        icon.classList.remove('fa-bars');
        icon.classList.add('fa-times');
    } else {
        menu.classList.add('hidden');
        icon.classList.remove('fa-times');
        icon.classList.add('fa-bars');
    }
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const menu = document.getElementById('mobile-menu');
    const button = document.getElementById('mobile-menu-button');
    
    if (!menu.contains(event.target) && !button.contains(event.target)) {
        menu.classList.add('hidden');
        const icon = button.querySelector('i');
        icon.classList.remove('fa-times');
        icon.classList.add('fa-bars');
    }
});
</script>