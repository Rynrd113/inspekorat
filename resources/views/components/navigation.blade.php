<!-- Navigation -->
<header class="bg-white shadow-sm sticky top-0 z-50 w-screen" style="margin-left: calc(-50vw + 50%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="{{ route('public.index') }}" class="inline-block">
                        <x-site-logo variant="header" size="md" :show-text="true" />
                    </a>
                </div>
            </div>

            <nav class="hidden md:flex md:items-center md:space-x-1">
                <a href="{{ route('public.index') }}"
                   class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('public.index') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Beranda
                </a>

                <!-- Informasi Dropdown -->
                <div class="relative group">
                    <button class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium flex items-center rounded-md transition-colors">
                        Informasi
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="py-1">
                            <a href="{{ route('public.profil') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Profil</a>
                            <a href="{{ route('public.berita.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Berita</a>
                            <a href="{{ route('public.galeri.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Galeri</a>
                            <a href="{{ route('public.portal-opd.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Portal OPD</a>
                            <a href="{{ route('public.review-opd') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Daftar Review OPD</a>
                        </div>
                    </div>
                </div>

                <!-- Layanan Dropdown -->
                <div class="relative group">
                    <button class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium flex items-center rounded-md transition-colors">
                        Layanan
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="py-1">
                            <a href="{{ route('public.pelayanan.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Pelayanan</a>
                            <a href="{{ route('public.dokumen.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Dokumen Publik</a>
                            <a href="{{ route('public.pengaduan') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Pengaduan Masyarakat</a>
                            <a href="{{ route('public.wbs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Sistem Pelaporan</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('public.faq') }}"
                   class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('public.faq') ? 'text-blue-600 bg-blue-50' : '' }}">
                    FAQ
                </a>
                <a href="{{ route('public.statistik') }}"
                   class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('public.statistik') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Statistik
                </a>
                <a href="{{ route('public.kontak') }}"
                   class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('public.kontak') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Kontak
                </a>
            </nav>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button"
                        class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600 p-2 rounded-md"
                        id="mobile-menu-button"
                        aria-expanded="false"
                        aria-label="Toggle menu">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t border-gray-200">
            <a href="{{ route('public.index') }}"
               class="block px-3 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.index') ? 'text-blue-600 bg-blue-50' : '' }}">
               <i class="fas fa-home mr-2"></i>Beranda
            </a>

             <!-- Informasi Section -->
             <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Informasi</div>
             <a href="{{ route('public.profil') }}"
                class="block px-6 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.profil') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-user mr-2"></i>Profil
             </a>
             <a href="{{ route('public.berita.index') }}"
                class="block px-6 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.berita.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-newspaper mr-2"></i>Berita
             </a>
             <a href="{{ route('public.galeri.index') }}"
                class="block px-6 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.galeri.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-images mr-2"></i>Galeri
             </a>
             <a href="{{ route('public.portal-opd.index') }}"
                class="block px-6 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.portal-opd.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-building mr-2"></i>Portal OPD
             </a>
             <a href="{{ route('public.review-opd') }}"
                class="block px-6 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.review-opd') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-clipboard-check mr-2"></i>Daftar Review OPD
             </a>

             <!-- Layanan Section -->
             <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Layanan</div>
             <a href="{{ route('public.pelayanan.index') }}"
                class="block px-6 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.pelayanan.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-concierge-bell mr-2"></i>Pelayanan
             </a>
             <a href="{{ route('public.dokumen.index') }}"
                class="block px-6 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.dokumen.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-file-pdf mr-2"></i>Dokumen Publik
             </a>
             <a href="{{ route('public.pengaduan') }}"
                class="block px-6 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.pengaduan') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-exclamation-circle mr-2"></i>Pengaduan Masyarakat
             </a>
             <a href="{{ route('public.wbs') }}"
                class="block px-6 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.wbs') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-list-check mr-2"></i>Sistem Pelaporan
             </a>

             <!-- Other -->
             <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bantuan</div>
             <a href="{{ route('public.faq') }}"
                class="block px-3 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.faq') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-question-circle mr-2"></i>FAQ
             </a>
             <a href="{{ route('public.statistik') }}"
                class="block px-3 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.statistik') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-chart-bar mr-2"></i>Statistik
             </a>
             <a href="{{ route('public.kontak') }}"
                class="block px-3 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('public.kontak') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-headset mr-2"></i>Kontak
             </a>
        </div>
    </div>
</header>
