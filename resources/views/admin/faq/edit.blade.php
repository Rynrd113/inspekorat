@extends('layouts.admin')

@section('title', 'Edit FAQ')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit FAQ</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
                <li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
                <li><a href="{{ route('admin.faq.index') }}" class="text-blue-600 hover:text-blue-800">FAQ</a></li>
                <li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
                <li class="text-gray-600">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-edit mr-2 text-blue-600"></i>
                Form Edit FAQ
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.faq.update', $faq->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-3">
                        <label for="pertanyaan" class="block text-sm font-medium text-gray-700 mb-2">
                            Pertanyaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pertanyaan') border-red-500 @enderror" 
                               id="pertanyaan" 
                               name="pertanyaan" 
                               value="{{ old('pertanyaan', $faq->pertanyaan ?? 'Apa itu Inspektorat Papua Tengah?') }}" 
                               required>
                        @error('pertanyaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-1">
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kategori') border-red-500 @enderror" 
                                id="kategori" 
                                name="kategori" 
                                required>
                            <option value="">Pilih Kategori</option>
                            <option value="umum" {{ old('kategori', $faq->kategori ?? 'umum') == 'umum' ? 'selected' : '' }}>Umum</option>
                            <option value="layanan" {{ old('kategori', $faq->kategori ?? '') == 'layanan' ? 'selected' : '' }}>Layanan</option>
                            <option value="pengaduan" {{ old('kategori', $faq->kategori ?? '') == 'pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                            <option value="audit" {{ old('kategori', $faq->kategori ?? '') == 'audit' ? 'selected' : '' }}>Audit</option>
                            <option value="lainnya" {{ old('kategori', $faq->kategori ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="jawaban" class="block text-sm font-medium text-gray-700 mb-2">
                        Jawaban <span class="text-red-500">*</span>
                    </label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jawaban') border-red-500 @enderror" 
                              id="jawaban" 
                              name="jawaban" 
                              rows="6" 
                              required>{{ old('jawaban', $faq->jawaban ?? 'Inspektorat Papua Tengah adalah lembaga pengawasan internal pemerintah yang bertugas melakukan audit, reviu, evaluasi, pemantauan, dan kegiatan pengawasan lainnya terhadap penyelenggaraan tugas dan fungsi organisasi.') }}</textarea>
                    @error('jawaban')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Gunakan formatting HTML sederhana jika diperlukan (contoh: &lt;b&gt;, &lt;i&gt;, &lt;br&gt;, &lt;ul&gt;, &lt;li&gt;).</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2">
                            Urutan Tampil
                        </label>
                        <input type="number" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('urutan') border-red-500 @enderror" 
                               id="urutan" 
                               name="urutan" 
                               value="{{ old('urutan', $faq->urutan ?? 1) }}" 
                               min="1">
                        @error('urutan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Semakin kecil angka, semakin di atas posisinya.</p>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="aktif" {{ old('status', $faq->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $faq->status ?? '') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="is_featured" class="block text-sm font-medium text-gray-700 mb-2">
                            FAQ Unggulan
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('is_featured') border-red-500 @enderror" 
                                id="is_featured" 
                                name="is_featured">
                            <option value="0" {{ old('is_featured', $faq->is_featured ?? '0') == '0' ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('is_featured', $faq->is_featured ?? '') == '1' ? 'selected' : '' }}>Ya</option>
                        </select>
                        @error('is_featured')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">FAQ unggulan akan ditampilkan lebih menonjol.</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                        Tags
                    </label>
                    <input type="text" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tags') border-red-500 @enderror" 
                           id="tags" 
                           name="tags" 
                           value="{{ old('tags', $faq->tags ?? 'inspektorat, profil, tugas') }}" 
                           placeholder="Pisahkan dengan koma">
                    @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Contoh: pengaduan, prosedur, audit</p>
                </div>

                <!-- Statistics and Preview Section -->
                @if(isset($faq->created_at))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Statistik FAQ</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div><strong>Dibuat:</strong> {{ $faq->created_at->format('d F Y H:i') }}</div>
                            <div><strong>Diperbarui:</strong> {{ $faq->updated_at->format('d F Y H:i') }}</div>
                            <div><strong>Views:</strong> {{ $faq->views ?? 0 }} kali dilihat</div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Preview FAQ</h4>
                        <div class="bg-white rounded-lg border border-gray-200">
                            <div class="border-b border-gray-200">
                                <div class="px-4 py-3">
                                    <div id="previewPertanyaan" class="font-medium text-gray-900">{{ $faq->pertanyaan ?? 'Apa itu Inspektorat Papua Tengah?' }}</div>
                                </div>
                            </div>
                            <div class="px-4 py-3">
                                <div id="previewJawaban" class="text-gray-700">{!! $faq->jawaban ?? 'Inspektorat Papua Tengah adalah lembaga pengawasan internal pemerintah...' !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.faq.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i> Update
                    </button>
                </div>
            </form>
        </div>
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
</script>
@endpush
@endsection
