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

    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-eye mr-2 text-blue-600"></i>
                    Detail Pelayanan
                </h2>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.pelayanan.edit', $pelayanan->id) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <button type="button" onclick="confirmDelete({{ $pelayanan->id }})" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Nama Pelayanan</h3>
                                <p class="text-lg font-semibold text-gray-900">{{ $pelayanan->nama ?? 'Layanan Audit Internal' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Kategori</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($pelayanan->kategori ?? 'audit') }}
                                </span>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Waktu Layanan</h3>
                                <p class="text-gray-900">{{ $pelayanan->waktu_layanan ?? '7 hari kerja' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Biaya</h3>
                                <p class="text-gray-900">{{ $pelayanan->biaya ?? 'Gratis' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Kontak</h3>
                                <p class="text-gray-900">{{ $pelayanan->kontak ?? 'inspektorat@paputengah.go.id' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                                @if(($pelayanan->status ?? 'aktif') == 'aktif')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Non-aktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    @if(isset($pelayanan->gambar) && $pelayanan->gambar)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $pelayanan->gambar) }}" 
                                 alt="{{ $pelayanan->nama ?? 'Layanan' }}" 
                                 class="w-full h-64 object-cover rounded-lg border border-gray-300">
                        </div>
                    @else
                        <div class="text-center">
                            <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-300">
                                <div class="text-gray-400">
                                    <i class="fas fa-image text-4xl mb-2"></i>
                                    <p class="text-sm">Tidak ada gambar</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 space-y-6">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Deskripsi</h3>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $pelayanan->deskripsi ?? 'Layanan audit internal untuk memastikan tata kelola yang baik dan akuntabilitas dalam organisasi pemerintahan.' }}
                    </p>
                </div>

                @if(isset($pelayanan->syarat) && $pelayanan->syarat)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Syarat & Ketentuan</h3>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $pelayanan->syarat }}
                    </p>
                </div>
                @endif

                @if(isset($pelayanan->prosedur) && $pelayanan->prosedur)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Prosedur</h3>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $pelayanan->prosedur }}
                    </p>
                </div>
                @endif

                <div class="pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.pelayanan.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
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
                            Konfirmasi Hapus
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus pelayanan "<strong>{{ $pelayanan->nama ?? 'ini' }}</strong>"?
                                <br><br>
                                <small class="text-gray-400">Tindakan ini tidak dapat dibatalkan.</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" action="{{ route('admin.pelayanan.destroy', $pelayanan->id ?? 1) }}" method="POST" class="inline">
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
    document.getElementById('deleteForm').action = `{{ route('admin.pelayanan.index') }}/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
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
