<!-- Footer -->
<footer class="bg-gradient-to-br from-gray-800 via-gray-900 to-gray-900 text-white mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Brand Section -->
            <div class="col-span-1 lg:col-span-1">
                <div class="flex items-center mb-4">
                    <x-site-logo variant="footer" size="md" :show-text="true" class="text-white" />
                </div>
                <p class="text-gray-300 text-sm leading-relaxed">
                    Portal resmi Inspektorat Provinsi Papua Tengah untuk layanan publik dan informasi pemerintahan.
                </p>
            </div>
            
            <!-- Informasi Links -->
            <div>
                <h5 class="font-bold text-white mb-4">Informasi</h5>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('public.profil') }}" 
                           class="text-gray-300 hover:text-white text-sm transition-colors">
                            Profil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('public.berita.index') }}" 
                           class="text-gray-300 hover:text-white text-sm transition-colors">
                            Berita
                        </a>
                    </li>
                    @if(Route::has('public.galeri.index'))
                    <li>
                        <a href="{{ route('public.galeri.index') }}" 
                           class="text-gray-300 hover:text-white text-sm transition-colors">
                            Galeri
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            
            <!-- Layanan Links -->
            <div>
                <h5 class="font-bold text-white mb-4">Layanan</h5>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('public.pelayanan.index') }}" 
                           class="text-gray-300 hover:text-white text-sm transition-colors">
                            Pelayanan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('public.dokumen.index') }}" 
                           class="text-gray-300 hover:text-white text-sm transition-colors">
                            Dokumen
                        </a>
                    </li>
                    @if(Route::has('public.pengaduan'))
                    <li>
                        <a href="{{ route('public.pengaduan') }}" 
                           class="text-gray-300 hover:text-white text-sm transition-colors">
                            Pengaduan
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('public.wbs') }}" 
                           class="text-gray-300 hover:text-white text-sm transition-colors">
                            WBS
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Bantuan & Social Links -->
            <div>
                <h5 class="font-bold text-white mb-4">Bantuan</h5>
                <ul class="space-y-2 mb-6">
                    <li>
                        <a href="{{ route('public.faq') }}" 
                           class="text-gray-300 hover:text-white text-sm transition-colors">
                            FAQ
                        </a>
                    </li>
                    @if(Route::has('public.kontak'))
                    <li>
                        <a href="{{ route('public.kontak') }}" 
                           class="text-gray-300 hover:text-white text-sm transition-colors">
                            Kontak
                        </a>
                    </li>
                    @endif
                </ul>
                
                <!-- Social Media -->
                <div>
                    <h6 class="font-medium text-white mb-3">Ikuti Kami</h6>
                    <div class="flex space-x-3">
                        <a href="#" 
                           class="w-8 h-8 bg-gray-700 hover:bg-blue-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" 
                           class="w-8 h-8 bg-gray-700 hover:bg-blue-400 rounded-full flex items-center justify-center transition-colors">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                        <a href="https://www.instagram.com/inspektoratpapuatengah?igsh=MWN6aHpoeGRwNGhydg==" 
                           target="_blank"
                           class="w-8 h-8 bg-gray-700 hover:bg-pink-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                        <a href="#" 
                           class="w-8 h-8 bg-gray-700 hover:bg-red-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fab fa-youtube text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <hr class="my-8 border-gray-700">
        
        <div class="flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-300 text-sm mb-4 md:mb-0">
                &copy; {{ date('Y') }} Inspektorat Provinsi Papua Tengah. All rights reserved.
            </p>
            <p class="text-gray-400 text-sm">
                Powered by Laravel & Tailwind CSS
            </p>
        </div>
    </div>
</footer>