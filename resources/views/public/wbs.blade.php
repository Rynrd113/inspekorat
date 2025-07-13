@extends('layouts.app')

@section('title', 'Whistleblower System (WBS) - Inspektorat Papua Tengah')
@section('description', 'Laporkan dugaan pelanggaran atau tindakan yang tidak sesuai melalui sistem Whistleblower Inspektorat Papua Tengah.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('public.index') }}" class="flex items-center">
                        <div class="h-8 w-8 bg-blue-600 rounded flex items-center justify-center">
                            <span class="text-white text-sm font-bold">PT</span>
                        </div>
                        <div class="ml-3">
                            <h1 class="text-lg font-semibold text-gray-900">Inspektorat Provinsi</h1>
                            <p class="text-sm text-gray-500">Papua Tengah</p>
                        </div>
                    </a>
                </div>
                
                <nav class="hidden md:block">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('public.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Beranda</a>
                        <a href="{{ route('admin.login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">Admin</a>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                </div>
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Whistleblower System (WBS)
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Laporkan dugaan pelanggaran atau tindakan yang tidak sesuai dengan peraturan melalui sistem ini. Identitas Anda akan dijaga kerahasiaan.
                </p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <x-alert type="success" class="mb-8">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if(session('error'))
                <x-alert type="error" class="mb-8">
                    {{ session('error') }}
                </x-alert>
            @endif

      <!-- Form -->
            <x-card class="max-w-2xl mx-auto">
                <form id="wbs-form" action="{{ route('public.wbs') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <!-- Anonymous Option -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" class="form-checkbox h-4 w-4 text-blue-600 rounded" value="1">
                                <span class="ml-2 text-sm text-gray-700">Laporan Anonim (Identitas tidak akan ditampilkan)</span>
                            </label>
                        </div>

                        <!-- Nama Pelapor -->
                        <div id="nama-field">
                            <x-input
                                label="Nama Pelapor *"
                                name="nama_pelapor"
                                type="text"
                                :value="old('nama_pelapor')"
                                required
                                placeholder="Masukkan nama lengkap Anda"
                            />
                            @error('nama_pelapor')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div id="email-field">
                            <x-input
                                label="Email *"
                                name="email"
                                type="email"
                                :value="old('email')"
                                required
                                placeholder="Masukkan alamat email Anda"
                            />
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. Telepon -->
                        <div id="telepon-field">
                            <x-input
                                label="No. Telepon"
                                name="no_telepon"
                                type="tel"
                                :value="old('no_telepon')"
                                placeholder="Masukkan nomor telepon Anda"
                            />
                            @error('no_telepon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subjek -->
                        <div>
                            <x-input
                                label="Subjek Laporan *"
                                name="subjek"
                                type="text"
                                :value="old('subjek')"
                                required
                                placeholder="Masukkan subjek laporan Anda"
                            />
                            @error('subjek')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Laporan *</label>
                            <textarea
                                id="deskripsi"
                                name="deskripsi"
                                rows="6"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Jelaskan secara detail laporan Anda..."
                                required
                            >{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lampiran -->
                        <div>
                            <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Lampiran (Opsional)</label>
                            <div class="space-y-3">
                                <!-- File input container -->
                                <div id="file-inputs-container">
                                    <div class="file-input-group flex items-center space-x-2">
                                        <input
                                            type="file"
                                            name="attachments[]"
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                        />
                                        <button
                                            type="button"
                                            onclick="removeFileInput(this)"
                                            class="px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                                            style="display: none;"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Add more files button -->
                                <button
                                    type="button"
                                    onclick="addFileInput()"
                                    class="inline-flex items-center px-3 py-2 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                >
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah File Lain
                                </button>
                            </div>
                            
                            <p class="mt-2 text-sm text-gray-500">
                                Format yang didukung: PDF, DOC, DOCX, JPG, JPEG, PNG (Maksimal 10MB per file)
                            </p>
                            @error('attachments')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('attachments.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6">
                            <x-button type="submit" variant="primary" size="lg" class="w-full">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim Laporan WBS
                            </x-button>
                        </div>
                    </div>
                </form>
            </x-card>

            <!-- Contact Information -->
            <div class="mt-12 text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Butuh Bantuan?</h3>
                <p class="text-gray-600 mb-4">
                    Jika Anda membutuhkan bantuan dalam menggunakan sistem ini, silakan hubungi kami:
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-8">
                    <div class="flex items-center">
                        <i class="fas fa-phone text-blue-600 mr-2"></i>
                        <span class="text-gray-700">(0984) 321234</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-blue-600 mr-2"></i>
                        <span class="text-gray-700">wbs@papuatengah.go.id</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const anonymousCheckbox = document.getElementById('is_anonymous');
    const namaField = document.getElementById('nama-field');
    const emailField = document.getElementById('email-field');
    const teleponField = document.getElementById('telepon-field');
    
    function toggleAnonymous() {
        if (anonymousCheckbox.checked) {
            namaField.style.display = 'none';
            emailField.style.display = 'none';
            teleponField.style.display = 'none';
            
            // Remove required attribute
            document.querySelector('input[name="nama_pelapor"]').removeAttribute('required');
            document.querySelector('input[name="email"]').removeAttribute('required');
        } else {
            namaField.style.display = 'block';
            emailField.style.display = 'block';
            teleponField.style.display = 'block';
            
            // Add required attribute
            document.querySelector('input[name="nama_pelapor"]').setAttribute('required', 'required');
            document.querySelector('input[name="email"]').setAttribute('required', 'required');
        }
    }
    
    anonymousCheckbox.addEventListener('change', toggleAnonymous);
    
    // File input management
    let fileInputCount = 1;
    const maxFiles = 5;
    
    window.addFileInput = function() {
        if (fileInputCount >= maxFiles) {
            alert(`Maksimal ${maxFiles} file yang dapat dilampirkan.`);
            return;
        }
        
        const container = document.getElementById('file-inputs-container');
        const newGroup = document.createElement('div');
        newGroup.className = 'file-input-group flex items-center space-x-2';
        
        newGroup.innerHTML = `
            <input
                type="file"
                name="attachments[]"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
            />
            <button
                type="button"
                onclick="removeFileInput(this)"
                class="px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
            >
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(newGroup);
        fileInputCount++;
        
        // Show remove buttons if more than one file input
        updateRemoveButtons();
        
        // Add file size validation
        const newInput = newGroup.querySelector('input[type="file"]');
        newInput.addEventListener('change', validateFileSize);
    };
    
    window.removeFileInput = function(button) {
        if (fileInputCount <= 1) {
            return;
        }
        
        button.closest('.file-input-group').remove();
        fileInputCount--;
        updateRemoveButtons();
    };
    
    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.file-input-group button');
        removeButtons.forEach(button => {
            button.style.display = fileInputCount > 1 ? 'block' : 'none';
        });
    }
    
    function validateFileSize(event) {
        const file = event.target.files[0];
        if (file) {
            const fileSize = file.size / 1024 / 1024; // Convert to MB
            if (fileSize > 10) {
                alert('Ukuran file terlalu besar. Maksimal 10MB per file.');
                event.target.value = '';
            }
        }
    }
    
    // Add file size validation to existing input
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', validateFileSize);
    });
    
    // Handle form submission
    document.getElementById('wbs-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate files
        const fileInputs = document.querySelectorAll('input[name="attachments[]"]');
        let totalSize = 0;
        let hasValidFiles = false;
        
        fileInputs.forEach(input => {
            if (input.files[0]) {
                hasValidFiles = true;
                totalSize += input.files[0].size;
            }
        });
        
        if (hasValidFiles && totalSize > 50 * 1024 * 1024) { // 50MB total limit
            alert('Total ukuran file terlalu besar. Maksimal 50MB untuk semua file.');
            return;
        }
        
        // Show loading
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
        submitButton.disabled = true;
        
        // For demo - replace with actual form submission
        setTimeout(() => {
            // Reset button
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            
            // Show success message
            alert('Laporan WBS berhasil dikirim! Terima kasih atas partisipasi Anda.');
            
            // Reset form and file inputs
            this.reset();
            resetFileInputs();
            toggleAnonymous();
        }, 2000);
    });
    
    function resetFileInputs() {
        const container = document.getElementById('file-inputs-container');
        const allGroups = container.querySelectorAll('.file-input-group');
        
        // Remove all but first group
        for (let i = 1; i < allGroups.length; i++) {
            allGroups[i].remove();
        }
        
        fileInputCount = 1;
        updateRemoveButtons();
    }
});
</script>
@endpush
@endsection
