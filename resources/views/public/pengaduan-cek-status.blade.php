@extends('layouts.public')

@section('title', 'Cek Status Pengaduan - Inspektorat Papua Tengah')

@section('description', 'Cek status pengaduan Anda menggunakan nomor tiket yang diberikan saat pengajuan.')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4">Cek Status Pengaduan</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                Masukkan nomor tiket dan email Anda untuk melihat perkembangan pengaduan
            </p>
        </div>
    </div>
</section>

<!-- Check Status Form -->
<section class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            @if($pengaduan)
                <!-- Status Result -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500">Nomor Tiket</p>
                            <p class="text-2xl font-bold text-gray-900">#{{ $pengaduan->id }}</p>
                        </div>
                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                            @if($pengaduan->status === 'diterima') bg-yellow-100 text-yellow-800
                            @elseif($pengaduan->status === 'proses') bg-blue-100 text-blue-800
                            @elseif($pengaduan->status === 'selesai') bg-green-100 text-green-800
                            @elseif($pengaduan->status === 'ditolak') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $pengaduan->status_label }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Subjek</p>
                            <p class="font-medium text-gray-900">{{ $pengaduan->subjek }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Kategori</p>
                            <p class="font-medium text-gray-900 capitalize">{{ $pengaduan->kategori }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tanggal Pengaduan</p>
                            <p class="font-medium text-gray-900">{{ $pengaduan->tanggal_pengaduan->format('d M Y H:i') }} WIT</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Pelapor</p>
                            <p class="font-medium text-gray-900">{{ $pengaduan->nama_pengadu }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500 mb-2">Isi Pengaduan</p>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $pengaduan->isi_pengaduan }}</p>
                        </div>
                    </div>

                    @if($pengaduan->tanggapan)
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-800 font-semibold mb-2">📝 Tanggapan dari Inspektorat</p>
                        <div class="bg-white p-4 rounded border border-green-100">
                            <p class="text-green-900 whitespace-pre-wrap">{{ $pengaduan->tanggapan }}</p>
                        </div>
                        @if($pengaduan->updated_at)
                        <p class="text-xs text-green-600 mt-2">Terakhir diperbarui: {{ $pengaduan->updated_at->format('d M Y H:i') }} WIT</p>
                        @endif
                    </div>
                    @endif

                    @if($pengaduan->bukti_files && count($pengaduan->bukti_files) > 0)
                    <div class="mb-6">
                        <p class="text-sm text-gray-500 mb-2">Bukti Pendukung</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($pengaduan->bukti_files as $index => $file)
                                <a href="{{ Storage::url($file) }}" target="_blank" class="block bg-gray-50 border border-gray-200 rounded-lg p-3 hover:border-blue-300 transition-colors">
                                    <p class="text-sm font-medium text-gray-700 truncate">{{ basename($file) }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Klik untuk lihat/unduh</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Status Timeline -->
                    <div class="mb-6">
                        <p class="text-sm text-gray-500 mb-4">Riwayat Status</p>
                        <div class="relative pl-4 border-l-2 border-gray-200">
                            <!-- Diterima -->
                            <div class="relative pb-6">
                                <div class="absolute left-[-10px] top-0 w-4 h-4 rounded-full border-4
                                    @if(in_array($pengaduan->status, ['diterima', 'proses', 'selesai', 'ditolak'])) bg-yellow-400 border-yellow-400 @else bg-gray-200 border-gray-200 @endif"
                                     style="background: white;"></div>
                                <div class="ml-2">
                                    <p class="font-medium
                                        @if(in_array($pengaduan->status, ['diterima', 'proses', 'selesai', 'ditolak'])) text-yellow-700 @else text-gray-400 @endif">
                                        Diterima
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $pengaduan->tanggal_pengaduan->format('d M Y H:i') }} WIT</p>
                                    <p class="text-xs text-gray-400 mt-1">Pengaduan telah diterima</p>
                                </div>
                            </div>

                            <!-- Proses -->
                            @if(in_array($pengaduan->status, ['proses', 'selesai']))
                            <div class="relative pb-6">
                                <div class="absolute left-[-10px] top-0 w-4 h-4 rounded-full border-4
                                    @if($pengaduan->status === 'proses') bg-blue-400 border-blue-400
                                    @elseif($pengaduan->status === 'selesai') bg-blue-400 border-blue-400
                                    @else bg-gray-200 border-gray-200 @endif"
                                     style="background: white;"></div>
                                <div class="ml-2">
                                    <p class="font-medium
                                        @if(in_array($pengaduan->status, ['proses', 'selesai'])) text-blue-700 @else text-gray-400 @endif">
                                        Proses
                                    </p>
                                    @if($pengaduan->updated_at && $pengaduan->status !== 'diterima')
                                    <p class="text-sm text-gray-500">{{ $pengaduan->updated_at->format('d M Y H:i') }} WIT</p>
                                    @else
                                    <p class="text-sm text-gray-500">-</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">Pengaduan sedang ditindaklanjuti</p>
                                </div>
                            </div>
                            @endif

                            <!-- Selesai -->
                            @if($pengaduan->status === 'selesai')
                            <div class="relative">
                                <div class="absolute left-[-10px] top-0 w-4 h-4 rounded-full bg-green-400 border-4 border-green-400" style="background: white;"></div>
                                <div class="ml-2">
                                    <p class="font-medium text-green-700">Selesai</p>
                                    @if($pengaduan->updated_at)
                                    <p class="text-sm text-gray-500">{{ $pengaduan->updated_at->format('d M Y H:i') }} WIT</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">Pengaduan telah selesai ditangani</p>
                                </div>
                            </div>
                            @endif

                            <!-- Ditolak -->
                            @if($pengaduan->status === 'ditolak')
                            <div class="relative">
                                <div class="absolute left-[-10px] top-0 w-4 h-4 rounded-full bg-red-400 border-4 border-red-400" style="background: white;"></div>
                                <div class="ml-2">
                                    <p class="font-medium text-red-700">Ditolak</p>
                                    @if($pengaduan->updated_at)
                                    <p class="text-sm text-gray-500">{{ $pengaduan->updated_at->format('d M Y H:i') }} WIT</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">Pengaduan ditolak</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('public.pengaduan') }}" class="inline-flex items-center px-6 py-3 text-blue-600 border-2 border-blue-600 rounded-xl hover:bg-blue-50 transition-colors font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Form Pengaduan
                        </a>
                    </div>

                </div>

            @else
                <!-- Search Form -->
                @if($error)
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p>{{ $error }}</p>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('public.pengaduan.cek-status.post') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="ticket" class="block text-sm font-medium text-gray-700 mb-2">Nomor Tiket <span class="text-red-500">*</span></label>
                        <input type="text" 
                               id="ticket" 
                               name="ticket" 
                               value="{{ old('ticket', $ticket ?? '') }}"
                               placeholder="Contoh: 123"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Nomor tiket yang Anda terima saat mengajukan pengaduan (hanya angka)</p>
                        @error('ticket')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="email@contoh.com"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               required>
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            Cek Status
                        </button>
                    </div>
                </form>

                <!-- Info Box -->
                <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2">ℹ️ Informasi</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Nomor tiket berupa angka (contoh: 1, 2, 123, dst)</li>
                        <li>• Gunakan email yang sama saat pengajuan pengaduan</li>
                        <li>• Pengaduan anonim tidak dapat dicek melalui fitur ini</li>
                        <li>• Jika lupa nomor tiket, hubungi kami via WhatsApp atau email</li>
                    </ul>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('public.pengaduan') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        ← Kembali ke Form Pengaduan
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Help Section -->
@if(!$pengaduan)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-12">Butuh Bantuan?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">WhatsApp</h3>
                <p class="text-gray-600">Hubungi kami langsung via WhatsApp untuk bantuan cepat</p>
                <a href="https://wa.me/6282345557656" target="_blank" class="inline-block mt-3 text-green-600 hover:text-green-700 font-medium">Chat via WhatsApp →</a>
            </div>
            <div class="text-center p-6">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                <p class="text-gray-600">Kirim email ke alamat resmi kami</p>
                <a href="mailto:{{ config('contact.email') }}" class="inline-block mt-3 text-blue-600 hover:text-blue-700 font-medium">Kirim Email →</a>
            </div>
            <div class="text-center p-6">
                <div class="w-16 h-16 mx-auto mb-4 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Datang Langsung</h3>
                <p class="text-gray-600">Kunjungi kantor Inspektorat Papua Tengah</p>
                <a href="{{ config('contact.lokasi.maps_url') }}" target="_blank" class="inline-block mt-3 text-purple-600 hover:text-purple-700 font-medium">Lihat Lokasi →</a>
            </div>
        </div>
    </div>
</section>
@endif
@endsection