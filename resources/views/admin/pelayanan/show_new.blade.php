@extends('layouts.admin')

@section('title', 'Detail Pelayanan')

@section('header', 'Detail Pelayanan')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.pelayanan.index') }}" class="text-blue-600 hover:text-blue-800">Pelayanan</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Detail</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $pelayanan->nama_pelayanan ?? 'Detail Pelayanan' }}</h1>
            <p class="text-gray-600">Informasi lengkap layanan publik</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pelayanan.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
            @if(auth()->user()->hasAnyRole(['admin_pelayanan', 'service_manager', 'admin', 'superadmin']))
            <a href="{{ route('admin.pelayanan.edit', $pelayanan->id ?? 1) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            @endif
        </div>
    </div>

    <!-- Service Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Service Overview -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Pelayanan</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelayanan</label>
                        <p class="text-lg font-medium text-gray-900">{{ $pelayanan->nama_pelayanan ?? 'Contoh Layanan Publik' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <div class="prose prose-sm max-w-none">
                            {!! $pelayanan->deskripsi ?? '<p>Deskripsi layanan akan ditampilkan di sini</p>' !!}
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Pelayanan</label>
                            <p class="text-sm text-gray-900">{{ $pelayanan->waktu_pelayanan ?? '3 hari kerja' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Biaya</label>
                            <p class="text-sm text-gray-900">{{ $pelayanan->biaya ?? 'Gratis' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Requirements -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Persyaratan</h2>
                </div>
                <div class="p-6">
                    @if(isset($pelayanan->persyaratan) && is_array($pelayanan->persyaratan))
                    <ul class="space-y-2">
                        @foreach($pelayanan->persyaratan as $index => $persyaratan)
                        <li class="flex items-start space-x-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-medium">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-gray-700">{{ $persyaratan }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="space-y-2">
                        <div class="flex items-start space-x-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-medium">1</span>
                            <span class="text-gray-700">Fotokopi KTP yang masih berlaku</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-medium">2</span>
                            <span class="text-gray-700">Surat permohonan</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-medium">3</span>
                            <span class="text-gray-700">Dokumen pendukung lainnya</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Procedure -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Prosedur Pelayanan</h2>
                </div>
                <div class="p-6">
                    @if(isset($pelayanan->prosedur) && is_array($pelayanan->prosedur))
                    <div class="space-y-4">
                        @foreach($pelayanan->prosedur as $index => $prosedur)
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-medium">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-700">{{ $prosedur }}</p>
                            </div>
                        </div>
                        @if($index < count($pelayanan->prosedur) - 1)
                        <div class="flex justify-center">
                            <i class="fas fa-arrow-down text-gray-400"></i>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @else
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-medium">1</div>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-700">Pemohon mengajukan permohonan dengan melengkapi berkas yang diperlukan</p>
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <i class="fas fa-arrow-down text-gray-400"></i>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-medium">2</div>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-700">Petugas melakukan verifikasi berkas dan persyaratan</p>
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <i class="fas fa-arrow-down text-gray-400"></i>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-700">Proses penyelesaian sesuai dengan waktu yang ditentukan</p>
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <i class="fas fa-arrow-down text-gray-400"></i>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-medium">4</div>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-700">Pemohon dapat mengambil hasil layanan</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Service Info -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Layanan</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            {{ $pelayanan->kategori ?? 'Layanan Publik' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if(($pelayanan->status ?? 'aktif') == 'aktif') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($pelayanan->status ?? 'Aktif') }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Pelayanan</label>
                        <p class="text-sm text-gray-900">{{ $pelayanan->waktu_pelayanan ?? '3 hari kerja' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Biaya</label>
                        <p class="text-sm text-gray-900">{{ $pelayanan->biaya ?? 'Gratis' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dibuat</label>
                        <p class="text-sm text-gray-900">{{ $pelayanan->created_at->format('d F Y') ?? date('d F Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab</label>
                        <p class="text-sm text-gray-900">{{ $pelayanan->penanggung_jawab ?? 'Admin' }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Kontak Pelayanan</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone text-green-600"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Telepon</p>
                            <p class="text-sm text-gray-600">{{ $pelayanan->telepon ?? '(0984) 123456' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-blue-600"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Email</p>
                            <p class="text-sm text-gray-600">{{ $pelayanan->email ?? 'layanan@inspektorat.go.id' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-map-marker-alt text-red-600"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Lokasi</p>
                            <p class="text-sm text-gray-600">{{ $pelayanan->lokasi ?? 'Kantor Inspektorat' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if(auth()->user()->hasAnyRole(['admin_pelayanan', 'service_manager', 'admin', 'superadmin']))
                    <a href="{{ route('admin.pelayanan.edit', $pelayanan->id ?? 1) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Pelayanan
                    </a>
                    
                    <button onclick="confirmDelete({{ $pelayanan->id ?? 1 }})" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Pelayanan
                    </button>
                    @endif
                    
                    <a href="{{ route('admin.pelayanan.index') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Statistik</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Permohonan</span>
                        <span class="text-sm font-medium text-gray-900">{{ $pelayanan->total_permohonan ?? '0' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Bulan Ini</span>
                        <span class="text-sm font-medium text-gray-900">{{ $pelayanan->permohonan_bulan_ini ?? '0' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Rata-rata Waktu</span>
                        <span class="text-sm font-medium text-gray-900">{{ $pelayanan->rata_rata_waktu ?? '2 hari' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Hapus Pelayanan
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus pelayanan ini? Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/admin/pelayanan/${id}`;
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
