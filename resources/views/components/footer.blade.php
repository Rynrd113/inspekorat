<!-- Footer -->
<footer class="bg-gradient-to-br from-gray-800 via-gray-900 to-gray-900 text-white mt-auto w-screen" style="margin-left: calc(-50vw + 50%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 lg:gap-12">
            <!-- Brand Section -->
            <div class="col-span-1 lg:col-span-1">
                <div class="flex items-center mb-4">
                    <x-site-logo variant="footer" size="md" :show-text="true" class="text-white" />
                </div>
                <p class="text-gray-300 text-xs sm:text-sm leading-relaxed">
                    Portal resmi Inspektorat Provinsi Papua Tengah untuk layanan publik dan informasi pemerintahan.
                </p>
            </div>

            <!-- Informasi Links -->
            <div>
                <h5 class="font-bold text-white mb-4 text-sm sm:text-base">Informasi</h5>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('public.profil') }}"
                           class="text-gray-300 hover:text-white text-xs sm:text-sm transition-colors">
                            Profil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('public.berita.index') }}"
                           class="text-gray-300 hover:text-white text-xs sm:text-sm transition-colors">
                            Berita
                        </a>
                    </li>
                    @if(Route::has('public.galeri.index'))
                    <li>
                        <a href="{{ route('public.galeri.index') }}"
                           class="text-gray-300 hover:text-white text-xs sm:text-sm transition-colors">
                            Galeri
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

             <!-- Layanan Links -->
             <div>
                 <h5 class="font-bold text-white mb-4 text-sm sm:text-base">Layanan</h5>
                 <ul class="space-y-2">
                     <li>
                         <a href="{{ route('public.pelayanan.index') }}"
                            class="text-gray-300 hover:text-white text-xs sm:text-sm transition-colors">
                             Pelayanan
                         </a>
                     </li>
                     <li>
                         <a href="{{ route('public.dokumen.index') }}"
                            class="text-gray-300 hover:text-white text-xs sm:text-sm transition-colors">
                             Dokumen Publik
                         </a>
                     </li>
                     @if(Route::has('public.pengaduan'))
                     <li>
                         <a href="{{ route('public.pengaduan') }}"
                            class="text-gray-300 hover:text-white text-xs sm:text-sm transition-colors">
                             Pengaduan Masyarakat
                         </a>
                     </li>
                     @endif
                     <li>
                         <a href="{{ route('public.wbs') }}"
                            class="text-gray-300 hover:text-white text-xs sm:text-sm transition-colors">
                             Sistem Pelaporan
                         </a>
                     </li>
                 </ul>
             </div>

            <!-- Bantuan & Social Links -->
            <div>
                <h5 class="font-bold text-white mb-4 text-sm sm:text-base">Bantuan</h5>
                <ul class="space-y-2 mb-6">
                    <li>
                        <a href="{{ route('public.faq') }}"
                           class="text-gray-300 hover:text-white text-xs sm:text-sm transition-colors">
                            FAQ
                        </a>
                    </li>
                    @if(Route::has('public.kontak'))
                    <li>
                        <a href="{{ route('public.kontak') }}"
                           class="text-gray-300 hover:text-white text-xs sm:text-sm transition-colors">
                            Kontak
                        </a>
                    </li>
                    @endif
                </ul>

                <!-- Social Media -->
                <div>
                    <h6 class="font-medium text-white mb-3 text-xs sm:text-sm">Ikuti Kami</h6>
                    <div class="flex space-x-2 sm:space-x-3">
                        <a href="#"
                           class="w-7 h-7 sm:w-8 sm:h-8 bg-gray-700 hover:bg-blue-600 rounded-full flex items-center justify-center transition-colors flex-shrink-0">
                            <i class="fab fa-facebook-f text-xs"></i>
                        </a>
                        <a href="#"
                           class="w-7 h-7 sm:w-8 sm:h-8 bg-gray-700 hover:bg-blue-400 rounded-full flex items-center justify-center transition-colors flex-shrink-0">
                            <i class="fab fa-twitter text-xs"></i>
                        </a>
                        <a href="https://www.instagram.com/inspektoratpapuatengah?igsh=MWN6aHpoeGRwNGhydg=="
                           target="_blank"
                           rel="noopener noreferrer"
                           class="w-7 h-7 sm:w-8 sm:h-8 bg-gray-700 hover:bg-pink-600 rounded-full flex items-center justify-center transition-colors flex-shrink-0">
                            <i class="fab fa-instagram text-xs"></i>
                        </a>
                        <a href="#"
                           class="w-7 h-7 sm:w-8 sm:h-8 bg-gray-700 hover:bg-red-600 rounded-full flex items-center justify-center transition-colors flex-shrink-0">
                            <i class="fab fa-youtube text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-6 sm:my-8 border-gray-700">

        <div class="flex flex-col xs:flex-col sm:flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-300 text-xs sm:text-sm text-center md:text-left">
                &copy; {{ date('Y') }} Inspektorat Provinsi Papua Tengah. All rights reserved.
            </p>
            <p class="text-gray-400 text-xs sm:text-sm text-center md:text-right">
                Powered by Laravel & Tailwind CSS
            </p>
        </div>
    </div>
</footer>
