@extends('layouts.public')

@section('title', 'Pengaduan Masyarakat - Portal Inspektorat Papua Tengah')
@section('description', 'Sampaikan pengaduan, kritik, dan saran kepada Inspektorat Provinsi Papua Tengah secara online.')

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

                    <form id="pengaduan-form" action="{{ route('public.pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori Pengaduan <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori" id="kategori" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Pilih Kategori</option>
                                <option value="pelayanan">Pelayanan Publik</option>
                                <option value="keuangan">Keuangan Daerah</option>
                                <option value="kepegawaian">Kepegawaian</option>
                                <option value="pengadaan">Pengadaan Barang/Jasa</option>
                                <option value="gratifikasi">Gratifikasi</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
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
                                        <label for="bukti_files" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                            <span>Upload file</span>
                                            <input id="bukti_files" name="bukti_files[]" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" multiple>
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, PDF, DOC hingga 5MB (bisa pilih lebih dari 1 file)</p>
                                    <div id="file-list" class="mt-2 text-left"></div>
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
                            <span class="text-gray-600">JGG4+65R, Jl. Ahmad Yani, Karang Tumaritis, Distrik Nabire, Kabupaten Nabire, Papua Tengah 98811</span>
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
        
        const response = await fetch('{{ route("public.pengaduan.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData
        });
        
        let result;
        const contentType = response.headers.get('content-type');
        
        if (contentType && contentType.includes('application/json')) {
            result = await response.json();
        } else {
            const text = await response.text();
            console.error('Response is not JSON:', text);
            throw new Error('Server error. Response: ' + text.substring(0, 100));
        }
        
        if (response.ok && result.success) {
            // Success
            showAlert('success', result.message || 'Pengaduan berhasil dikirim! Kami akan menindaklanjuti pengaduan Anda segera.');
            this.reset();
            document.getElementById('file-list').innerHTML = '';
        } else {
            // Error with validation
            let errorMsg = result.message || 'Terjadi kesalahan saat mengirim pengaduan';
            
            // Handle validation errors
            if (result.errors) {
                const errors = Object.values(result.errors).flat();
                errorMsg = errors.join('<br>');
            }
            
            throw new Error(errorMsg);
        }
    } catch (error) {
        console.error('Error:', error);
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
    
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.rounded-lg.border.p-4.mb-8');
    existingAlerts.forEach(alert => alert.remove());
    
    // Insert alert at the top of form container
    const formCard = document.getElementById('pengaduan-form').closest('.lg\\:col-span-2');
    formCard.insertAdjacentHTML('beforebegin', alertHtml);
    
    // Scroll to alert
    const alert = formCard.previousElementSibling;
    alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Reset form function
function resetForm() {
    document.getElementById('pengaduan-form').reset();
}

// File upload handler
document.getElementById('bukti_files').addEventListener('change', function(e) {
    const files = e.target.files;
    const fileList = document.getElementById('file-list');
    fileList.innerHTML = '';
    
    if (files.length > 0) {
        const ul = document.createElement('ul');
        ul.className = 'text-xs space-y-1';
        
        Array.from(files).forEach((file, index) => {
            const fileSize = file.size / 1024 / 1024; // MB
            if (fileSize > 5) {
                showAlert('error', `File "${file.name}" terlalu besar. Maksimal 5MB`);
                this.value = '';
                return;
            }
            
            const li = document.createElement('li');
            li.className = 'text-gray-600 flex items-center';
            li.innerHTML = `<i class="fas fa-file text-green-500 mr-2"></i>${file.name} (${fileSize.toFixed(2)} MB)`;
            ul.appendChild(li);
        });
        
        fileList.appendChild(ul);
    }
});
</script>
@endpush
@endsection
