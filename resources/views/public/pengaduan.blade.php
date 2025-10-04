@extends('layouts.public')

@section('title', 'Pengaduan Masyarakat - Portal Inspektorat Papua Tengah')
@section('description', 'Sampaikan pengaduan, kritik, dan saran kepada Inspektorat                         <div class="flex items-start">
                            <i class="fas fa-envelope text-gray-400 mt-0.5 mr-3"></i>
                            <span class="text-gray-600">inspektorat@papuatengah.go.id</span>
                        </div>insi Papua Tengah secara online.')

@section('content')

<div class="min-h-screen bg-gray-50">

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('public.index') }}" class="text-gray-500 hover:text-gray-700">Beranda</a>
                    </li>
                    <li>
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    </li>
                    <li class="text-gray-900 font-medium">Pengaduan</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <i class="fas fa-comment-alt text-green-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                Pengaduan Masyarakat
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Sampaikan pengaduan, kritik, dan saran Anda kepada Inspektorat Provinsi Papua Tengah. 
                Kami akan menindaklanjuti setiap pengaduan yang masuk.
            </p>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <x-alert type="success" class="mb-8" dismissible>
                {{ session('success') }}
            </x-alert>
        @endif
        
        @if(session('error'))
            <x-alert type="error" class="mb-8" dismissible>
                {{ session('error') }}
            </x-alert>
        @endif

        <!-- Pengaduan Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form -->
            <div class="lg:col-span-2">
                <x-card>
                    <x-slot:header>
                        <h3 class="text-lg font-semibold text-gray-900">Formulir Pengaduan</h3>
                        <p class="text-sm text-gray-600 mt-1">Isi formulir di bawah dengan lengkap dan jelas</p>
                    </x-slot:header>

                    <form id="pengaduan-form" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input 
                                type="text" 
                                name="nama_pengadu" 
                                label="Nama Lengkap" 
                                placeholder="Masukkan nama lengkap Anda"
                                required 
                                id="nama_pengadu"
                            />
                            <x-input 
                                type="email" 
                                name="email" 
                                label="Email" 
                                placeholder="nama@email.com"
                                required 
                                id="email"
                            />
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input 
                                type="tel" 
                                name="telepon" 
                                label="No. Telepon" 
                                placeholder="08123456789"
                                id="telepon"
                            />
                            <x-input 
                                type="text" 
                                name="subjek" 
                                label="Subjek Pengaduan" 
                                placeholder="Ringkasan singkat pengaduan"
                                required 
                                id="subjek"
                            />
                        </div>
                        
                        <x-input 
                            type="textarea" 
                            name="isi_pengaduan" 
                            label="Isi Pengaduan" 
                            placeholder="Jelaskan pengaduan Anda secara detail..."
                            required 
                            rows="6"
                            id="isi_pengaduan"
                        />
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Lampiran (Opsional)
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="attachment" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                            <span>Upload file</span>
                                            <input id="attachment" name="attachment" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, PDF, DOC hingga 5MB</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <input id="agreement" name="agreement" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" required>
                            <label for="agreement" class="ml-2 block text-sm text-gray-900">
                                Saya menyetujui bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan.
                            </label>
                        </div>
                        
                        <div class="flex gap-4">
                            <x-button type="submit" variant="primary" size="lg" class="flex-1" id="submit-btn">
                                <i class="fas fa-paper-plane mr-2"></i>
                                <span id="submit-text">Kirim Pengaduan</span>
                            </x-button>
                            <x-button type="button" variant="secondary" size="lg" onclick="resetForm()">
                                <i class="fas fa-undo mr-2"></i>
                                Reset
                            </x-button>
                        </div>
                    </form>
                </x-card>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <x-card>
                    <x-slot:header>
                        <h4 class="font-semibold text-gray-900">Informasi Penting</h4>
                    </x-slot:header>
                    
                    <div class="space-y-4 text-sm">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                            <p class="text-gray-600">Pengaduan akan diproses maksimal 7 hari kerja</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-green-500 mt-0.5 mr-3"></i>
                            <p class="text-gray-600">Data Anda akan dijaga kerahasiaannya</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-bell text-yellow-500 mt-0.5 mr-3"></i>
                            <p class="text-gray-600">Notifikasi akan dikirim via email</p>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h4 class="font-semibold text-gray-900">Kontak Langsung</h4>
                    </x-slot:header>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 mr-3"></i>
                            <span class="text-gray-600">(0971) 321234</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 mr-3"></i>
                            <span class="text-gray-600">inspektorat@papuatengah.go.id</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-gray-400 mt-0.5 mr-3"></i>
                            <span class="text-gray-600">Jl. Trans Papua, Merauke, Papua Tengah 99611</span>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h4 class="font-semibold text-gray-900">Tips Pengaduan</h4>
                    </x-slot:header>
                    
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 text-xs mt-1 mr-2"></i>
                            Jelaskan kronologi dengan detail
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 text-xs mt-1 mr-2"></i>
                            Sertakan bukti jika ada
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 text-xs mt-1 mr-2"></i>
                            Gunakan bahasa yang sopan
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 text-xs mt-1 mr-2"></i>
                            Cantumkan kontak yang valid
                        </li>
                    </ul>
                </x-card>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Form submission dengan AJAX
document.getElementById('pengaduan-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const originalText = submitText.textContent;
    
    // Disable button dan tampilkan loading
    submitBtn.disabled = true;
    submitText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('/api/v1/pengaduans', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok) {
            // Success
            showAlert('success', result.message || 'Pengaduan berhasil dikirim!');
            this.reset();
        } else {
            // Error
            throw new Error(result.message || 'Terjadi kesalahan saat mengirim pengaduan');
        }
    } catch (error) {
        showAlert('error', error.message);
    } finally {
        // Re-enable button
        submitBtn.disabled = false;
        submitText.textContent = originalText;
    }
});

// Function untuk menampilkan alert
function showAlert(type, message) {
    const alertHtml = `
        <div class="rounded-lg border p-4 mb-8 ${type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'}">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'success' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'}" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">${message}</div>
                <div class="ml-auto pl-3">
                    <button type="button" class="inline-flex rounded-md p-1.5 hover:bg-gray-100" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Insert alert at the top of main content
    const mainContent = document.querySelector('.max-w-4xl');
    const firstChild = mainContent.children[1]; // After header section
    firstChild.insertAdjacentHTML('beforebegin', alertHtml);
    
    // Scroll to top to show alert
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Reset form function
function resetForm() {
    document.getElementById('pengaduan-form').reset();
}

// File upload handler
document.getElementById('attachment').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // MB
        if (fileSize > 5) {
            showAlert('error', 'Ukuran file tidak boleh lebih dari 5MB');
            this.value = '';
        }
    }
});
</script>
@endpush
@endsection
