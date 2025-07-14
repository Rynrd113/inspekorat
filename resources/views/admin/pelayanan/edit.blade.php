@extends('layouts.admin')

@section('header', 'Edit Pelayanan')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.pelayanan.index') }}" class="text-blue-600 hover:text-blue-800">Pelayanan</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Edit</li>
@endsection

@section('main-content')
    <x-admin.form-section 
        title="Form Edit Pelayanan" 
        description="Edit informasi pelayanan yang sudah ada"
        icon="fas fa-edit"
    >
        <form action="{{ route('admin.pelayanan.update', $pelayanan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input 
                    label="Nama Pelayanan"
                    name="nama_layanan"
                    value="{{ old('nama_layanan', $pelayanan->nama_layanan ?? '') }}"
                    required="true"
                    error="{{ $errors->first('nama_layanan') }}"
                />
                
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kategori') border-red-500 @enderror" 
                            id="kategori" 
                            name="kategori" 
                            required>
                        <option value="">Pilih Kategori</option>
                        <option value="audit" {{ old('kategori', $pelayanan->kategori ?? '') == 'audit' ? 'selected' : '' }}>Audit</option>
                        <option value="konsultasi" {{ old('kategori', $pelayanan->kategori ?? '') == 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                        <option value="pengaduan" {{ old('kategori', $pelayanan->kategori ?? '') == 'pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                        <option value="lainnya" {{ old('kategori', $pelayanan->kategori ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('kategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <x-input 
                    type="textarea"
                    label="Deskripsi"
                    name="deskripsi"
                    required="true"
                    error="{{ $errors->first('deskripsi') }}"
                    rows="4"
                >{{ old('deskripsi', $pelayanan->deskripsi ?? '') }}</x-input>
            </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="persyaratan" class="block text-sm font-medium text-gray-700 mb-2">
                            Syarat & Ketentuan
                        </label>
                        <div id="persyaratan-container">
                            @if(old('persyaratan', $pelayanan->persyaratan ?? []))
                                @foreach(old('persyaratan', $pelayanan->persyaratan ?? []) as $index => $persyaratan)
                                <div class="flex items-center mb-2 persyaratan-item">
                                    <input type="text" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('persyaratan.*') border-red-500 @enderror" 
                                           name="persyaratan[]" 
                                           value="{{ $persyaratan }}" 
                                           placeholder="Masukkan syarat">
                                    <button type="button" class="ml-2 px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-persyaratan" onclick="removePersyaratan(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="flex items-center mb-2 persyaratan-item">
                                    <input type="text" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           name="persyaratan[]" 
                                           placeholder="Masukkan syarat">
                                    <button type="button" class="ml-2 px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-persyaratan" onclick="removePersyaratan(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="mt-2 px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600" onclick="addPersyaratan()">
                            <i class="fas fa-plus mr-1"></i>Tambah Syarat
                        </button>
                        @error('persyaratan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('persyaratan.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="prosedur" class="block text-sm font-medium text-gray-700 mb-2">
                            Prosedur
                        </label>
                        <div id="prosedur-container">
                            @if(old('prosedur', $pelayanan->prosedur ?? []))
                                @foreach(old('prosedur', $pelayanan->prosedur ?? []) as $index => $prosedur)
                                <div class="flex items-center mb-2 prosedur-item">
                                    <span class="text-gray-500 mr-2">{{ $index + 1 }}.</span>
                                    <input type="text" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prosedur.*') border-red-500 @enderror" 
                                           name="prosedur[]" 
                                           value="{{ $prosedur }}" 
                                           placeholder="Masukkan langkah prosedur">
                                    <button type="button" class="ml-2 px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-prosedur" onclick="removeProsedur(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="flex items-center mb-2 prosedur-item">
                                    <span class="text-gray-500 mr-2">1.</span>
                                    <input type="text" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           name="prosedur[]" 
                                           placeholder="Masukkan langkah prosedur">
                                    <button type="button" class="ml-2 px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-prosedur" onclick="removeProsedur(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="mt-2 px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600" onclick="addProsedur()">
                            <i class="fas fa-plus mr-1"></i>Tambah Prosedur
                        </button>
                        @error('prosedur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('prosedur.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <x-input 
                    label="Waktu Pelayanan"
                    name="waktu_pelayanan"
                    value="{{ old('waktu_pelayanan', $pelayanan->waktu_pelayanan ?? '') }}"
                    placeholder="Contoh: 7 hari kerja"
                    error="{{ $errors->first('waktu_pelayanan') }}"
                />
                
                <x-input 
                    label="Biaya"
                    name="biaya"
                    value="{{ old('biaya', $pelayanan->biaya ?? '') }}"
                    placeholder="Contoh: Gratis atau Rp 50.000"
                    error="{{ $errors->first('biaya') }}"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <x-input 
                    label="Kontak Penanggung Jawab"
                    name="kontak_penanggung_jawab"
                    value="{{ old('kontak_penanggung_jawab', $pelayanan->kontak_penanggung_jawab ?? '') }}"
                    placeholder="Nomor telepon atau email"
                    error="{{ $errors->first('kontak_penanggung_jawab') }}"
                />
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center">
                        <input type="checkbox" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('status') border-red-500 @enderror" 
                               id="status" 
                               name="status" 
                               value="1"
                               {{ old('status', $pelayanan->status ?? false) ? 'checked' : '' }}>
                        <label for="status" class="ml-2 block text-sm text-gray-900">
                            Aktif
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Centang untuk mengaktifkan layanan</p>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

                <div class="mt-6">
                    <label for="file_formulir" class="block text-sm font-medium text-gray-700 mb-2">
                        File Formulir
                    </label>
                    @if(isset($pelayanan->file_formulir) && $pelayanan->file_formulir)
                        <div class="mb-3">
                            <a href="{{ asset('storage/' . $pelayanan->file_formulir) }}" 
                               target="_blank" 
                               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-50 hover:bg-gray-100">
                                <i class="fas fa-file-pdf mr-2 text-red-500"></i>
                                Lihat File Saat Ini
                            </a>
                            <p class="text-sm text-gray-500 mt-1">File formulir saat ini</p>
                        </div>
                    @endif
                    <input type="file" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('file_formulir') border-red-500 @enderror" 
                           id="file_formulir" 
                           name="file_formulir" 
                           accept=".pdf,.doc,.docx">
                    @error('file_formulir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Format: PDF, DOC, DOCX. Maksimal 2MB. Kosongkan jika tidak ingin mengubah file.</p>
                </div>
                
            <div class="mt-6">
                <x-input 
                    type="textarea"
                    label="Dasar Hukum"
                    name="dasar_hukum"
                    placeholder="Masukkan dasar hukum pelayanan"
                    error="{{ $errors->first('dasar_hukum') }}"
                    rows="3"
                >{{ old('dasar_hukum', $pelayanan->dasar_hukum ?? '') }}</x-input>
            </div>

            <x-admin.form-actions 
                :back-url="route('admin.pelayanan.index')"
                submit-label="Update"
                submit-icon="fas fa-save"
                :show-reset="true"
            />
        </form>
    </x-admin.form-section>

<script>
function addProsedur() {
    const container = document.getElementById('prosedur-container');
    const items = container.querySelectorAll('.prosedur-item');
    const newIndex = items.length + 1;
    
    const newItem = document.createElement('div');
    newItem.className = 'flex items-center mb-2 prosedur-item';
    newItem.innerHTML = `
        <span class="text-gray-500 mr-2">${newIndex}.</span>
        <input type="text" 
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
               name="prosedur[]" 
               placeholder="Masukkan langkah prosedur">
        <button type="button" class="ml-2 px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-prosedur" onclick="removeProsedur(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newItem);
}

function removeProsedur(button) {
    const item = button.closest('.prosedur-item');
    item.remove();
    updateProsedurNumbers();
}

function updateProsedurNumbers() {
    const items = document.querySelectorAll('.prosedur-item');
    items.forEach((item, index) => {
        const numberSpan = item.querySelector('span');
        if (numberSpan) {
            numberSpan.textContent = (index + 1) + '.';
        }
    });
}

function addPersyaratan() {
    const container = document.getElementById('persyaratan-container');
    
    const newItem = document.createElement('div');
    newItem.className = 'flex items-center mb-2 persyaratan-item';
    newItem.innerHTML = `
        <input type="text" 
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
               name="persyaratan[]" 
               placeholder="Masukkan syarat">
        <button type="button" class="ml-2 px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-persyaratan" onclick="removePersyaratan(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newItem);
}

function removePersyaratan(button) {
    const item = button.closest('.persyaratan-item');
    item.remove();
}
</script>
@endsection
