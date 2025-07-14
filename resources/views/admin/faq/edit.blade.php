@extends('layouts.admin')

@section('header', 'Edit FAQ')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><a href="{{ route('admin.faq.index') }}" class="text-blue-600 hover:text-blue-800">FAQ</a></li>
<li><span class="text-gray-500">Edit</span></li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit FAQ</h1>
            <p class="text-gray-600 mt-1">Ubah informasi FAQ yang sudah ada</p>
        </div>
        <a href="{{ route('admin.faq.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">
                <i class="fas fa-edit mr-2 text-blue-600"></i>
                Form Edit FAQ
            </h2>
        </div>
        
        <form action="{{ route('admin.faq.update', $faq->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Pertanyaan -->
                    <div>
                        <label for="pertanyaan" class="block text-sm font-medium text-gray-700 mb-2">
                            Pertanyaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pertanyaan') border-red-500 @enderror" 
                               id="pertanyaan" 
                               name="pertanyaan" 
                               value="{{ old('pertanyaan', $faq->pertanyaan) }}" 
                               required>
                        @error('pertanyaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jawaban -->
                    <div>
                        <label for="jawaban" class="block text-sm font-medium text-gray-700 mb-2">
                            Jawaban <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jawaban') border-red-500 @enderror" 
                            id="jawaban" 
                            name="jawaban" 
                            rows="8" 
                            required>{{ old('jawaban', $faq->jawaban) }}</textarea>
                        @error('jawaban')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Anda dapat menggunakan HTML dasar untuk formatting
                        </div>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                            Tags
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               id="tags" 
                               name="tags" 
                               value="{{ old('tags', $faq->tags) }}" 
                               placeholder="Pisahkan dengan koma, contoh: inspektorat, audit, pelayanan">
                        <div class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pisahkan tags dengan koma untuk memudahkan pencarian
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Kategori -->
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kategori') border-red-500 @enderror" 
                                id="kategori" 
                                name="kategori" 
                                required>
                            <option value="">Pilih Kategori</option>
                            <option value="umum" {{ old('kategori', $faq->kategori) == 'umum' ? 'selected' : '' }}>Umum</option>
                            <option value="layanan" {{ old('kategori', $faq->kategori) == 'layanan' ? 'selected' : '' }}>Layanan</option>
                            <option value="pengaduan" {{ old('kategori', $faq->kategori) == 'pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                            <option value="audit" {{ old('kategori', $faq->kategori) == 'audit' ? 'selected' : '' }}>Audit</option>
                            <option value="lainnya" {{ old('kategori', $faq->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                id="status" 
                                name="status">
                            <option value="1" {{ old('status', $faq->status ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status', $faq->status ? '1' : '0') == '0' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                    </div>

                    <!-- Urutan -->
                    <div>
                        <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2">
                            Urutan Tampil
                        </label>
                        <input type="number" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               id="urutan" 
                               name="urutan" 
                               value="{{ old('urutan', $faq->urutan) }}" 
                               min="1" 
                               placeholder="1">
                        <div class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Semakin kecil angka, semakin awal tampil
                        </div>
                    </div>

                    <!-- Is Featured -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                   id="is_featured" 
                                   name="is_featured" 
                                   value="1"
                                   {{ old('is_featured', $faq->is_featured) ? 'checked' : '' }}>
                            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                FAQ Unggulan
                            </label>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            FAQ unggulan akan ditampilkan di bagian atas
                        </div>
                    </div>

                    <!-- View Count (Read Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Views
                        </label>
                        <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-600">
                            {{ $faq->view_count ?? 0 }} views
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="bg-gray-50 p-4 rounded-md">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div>
                                <strong>Dibuat:</strong><br>
                                {{ $faq->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div>
                                <strong>Terakhir diubah:</strong><br>
                                {{ $faq->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-8">
                <a href="{{ route('admin.faq.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.faq.show', $faq->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>Preview
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Character counter for textarea
document.getElementById('jawaban').addEventListener('input', function() {
    const maxLength = 2000;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    
    // Create or update character counter
    let counter = document.getElementById('char-counter');
    if (!counter) {
        counter = document.createElement('div');
        counter.id = 'char-counter';
        counter.className = 'mt-1 text-sm text-gray-500';
        this.parentNode.appendChild(counter);
    }
    
    counter.textContent = `${currentLength} karakter`;
    if (remaining < 100) {
        counter.className = 'mt-1 text-sm text-red-500';
    } else {
        counter.className = 'mt-1 text-sm text-gray-500';
    }
});

// Auto-save draft functionality
let saveTimeout;
function autoSave() {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        const formData = new FormData(document.querySelector('form'));
        console.log('Auto-saving draft...');
        // Here you could implement auto-save functionality
    }, 2000);
}

// Add auto-save listeners
document.getElementById('pertanyaan').addEventListener('input', autoSave);
document.getElementById('jawaban').addEventListener('input', autoSave);
</script>
@endsection