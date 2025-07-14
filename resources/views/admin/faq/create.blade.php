@extends('layouts.admin')

@section('title', 'Tambah FAQ')

@section('header', 'Tambah FAQ')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.faq.index') }}" class="text-blue-600 hover:text-blue-800">FAQ</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Tambah</li>
@endsection

@section('main-content')

<div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-plus mr-2 text-blue-600"></i>
                Form Tambah FAQ
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.faq.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-3">
                        <x-input
                            label="Pertanyaan"
                            name="pertanyaan"
                            id="pertanyaan"
                            type="text"
                            value="{{ old('pertanyaan') }}"
                            required="true"
                            :error="$errors->first('pertanyaan')"
                        />
                    </div>
                    
                    <div class="md:col-span-1">
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors @error('kategori') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                id="kategori" 
                                name="kategori" 
                                required>
                            <option value="">Pilih Kategori</option>
                            @foreach(\App\Models\Faq::getKategoriOptions() as $value => $label)
                                <option value="{{ $value }}" {{ old('kategori') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <x-input
                        label="Jawaban"
                        name="jawaban"
                        id="jawaban"
                        type="textarea"
                        rows="6"
                        required="true"
                        :error="$errors->first('jawaban')"
                    >{{ old('jawaban') }}</x-input>
                    <p class="mt-1 text-sm text-gray-500">Gunakan formatting HTML sederhana jika diperlukan (contoh: &lt;b&gt;, &lt;i&gt;, &lt;br&gt;, &lt;ul&gt;, &lt;li&gt;).</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <x-input
                            label="Urutan Tampil"
                            name="urutan"
                            id="urutan"
                            type="number"
                            value="{{ old('urutan', 1) }}"
                            min="1"
                            :error="$errors->first('urutan')"
                        />
                        <p class="mt-1 text-sm text-gray-500">Semakin kecil angka, semakin di atas posisinya.</p>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors @error('status') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="is_featured" class="block text-sm font-medium text-gray-700 mb-2">
                            FAQ Unggulan
                        </label>
                        <select class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors @error('is_featured') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                id="is_featured" 
                                name="is_featured">
                            <option value="0" {{ old('is_featured') == '0' ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>Ya</option>
                        </select>
                        @error('is_featured')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">FAQ unggulan akan ditampilkan lebih menonjol.</p>
                    </div>
                </div>

                <div class="mt-6">
                    <x-input
                        label="Tags"
                        name="tags"
                        id="tags"
                        type="text"
                        value="{{ old('tags') }}"
                        placeholder="Pisahkan dengan koma"
                        :error="$errors->first('tags')"
                    />
                    <p class="mt-1 text-sm text-gray-500">Contoh: pengaduan, prosedur, audit</p>
                </div>

                <!-- Preview Section -->
                <div class="bg-gray-50 rounded-lg p-6 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Preview FAQ</h3>
                    <div class="bg-white rounded-lg border border-gray-200">
                        <div class="border-b border-gray-200">
                            <button type="button" class="w-full px-4 py-3 text-left flex items-center justify-between hover:bg-gray-50 focus:outline-none focus:bg-gray-50">
                                <span id="previewPertanyaan" class="font-medium text-gray-900">Pertanyaan akan muncul di sini...</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </button>
                        </div>
                        <div class="px-4 py-3">
                            <div id="previewJawaban" class="text-gray-700">Jawaban akan muncul di sini...</div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                    <x-button variant="secondary" href="{{ route('admin.faq.index') }}">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </x-button>
                    <x-button type="submit" variant="success">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </x-button>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
// Live preview functionality
document.getElementById('pertanyaan').addEventListener('input', function() {
    const pertanyaan = this.value || 'Pertanyaan akan muncul di sini...';
    document.getElementById('previewPertanyaan').textContent = pertanyaan;
});

document.getElementById('jawaban').addEventListener('input', function() {
    const jawaban = this.value || 'Jawaban akan muncul di sini...';
    document.getElementById('previewJawaban').innerHTML = jawaban;
});

// Character counter for jawaban
document.getElementById('jawaban').addEventListener('input', function() {
    const current = this.value.length;
    const max = 1000; // Adjust as needed
    
    // Remove existing counter
    const existingCounter = this.parentNode.querySelector('.char-counter');
    if (existingCounter) {
        existingCounter.remove();
    }
    
    // Add new counter
    const counter = document.createElement('div');
    counter.className = 'char-counter form-text text-end';
    counter.textContent = `${current} karakter`;
    
    if (current > max) {
        counter.className += ' text-danger';
    }
    
    this.parentNode.appendChild(counter);
});

// Auto-generate urutan based on kategori
document.getElementById('kategori').addEventListener('change', function() {
    // Here you could make an AJAX call to get the next order number for this category
    // For now, we'll just suggest a default
    const urutanField = document.getElementById('urutan');
    if (!urutanField.value || urutanField.value == '1') {
        // You could implement logic to suggest the next available order
        urutanField.value = 1;
    }
});
</script>
@endpush
@endsection
