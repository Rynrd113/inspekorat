@extends('layouts.admin')

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
    <div class="bg-white rounded-lg shadow p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $pelayanan->nama }}</h1>
                <p class="text-gray-600 mt-1">Detail layanan publik</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.pelayanan.edit', $pelayanan) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <button type="button"
                        onclick="deletePelayanan({{ $pelayanan->id }})"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Informasi Dasar
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Kategori</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $pelayanan->kategori_label }}
                        </span>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                        @if($pelayanan->status)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-times mr-1"></i>Nonaktif
                            </span>
                        @endif
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Waktu Penyelesaian</h3>
                        <p class="text-gray-900 font-medium">{{ $pelayanan->waktu_penyelesaian ?? '-' }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Biaya</h3>
                        <p class="text-gray-900 font-medium">
                            @if($pelayanan->biaya && is_numeric($pelayanan->biaya))
                                Rp {{ number_format((int)$pelayanan->biaya, 0, ',', '.') }}
                            @else
                                {{ $pelayanan->biaya ?: 'Gratis' }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-align-left mr-2 text-blue-600"></i>
                    Deskripsi
                </h2>
                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $pelayanan->deskripsi }}</p>
            </div>

            <!-- Persyaratan -->
            @if($pelayanan->persyaratan)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list mr-2 text-blue-600"></i>
                    Syarat & Ketentuan
                </h2>
                <ul class="space-y-2">
                    @if(is_array($pelayanan->persyaratan))
                        @foreach($pelayanan->persyaratan as $item)
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                <span class="text-gray-700">{{ $item }}</span>
                            </li>
                        @endforeach
                    @else
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                            <span class="text-gray-700 whitespace-pre-wrap">{{ $pelayanan->persyaratan }}</span>
                        </li>
                    @endif
                </ul>
            </div>
            @endif

            <!-- Prosedur -->
            @if($pelayanan->prosedur)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-tasks mr-2 text-blue-600"></i>
                    Prosedur
                </h2>
                @if(is_array($pelayanan->prosedur))
                    <ol class="space-y-2">
                        @foreach($pelayanan->prosedur as $index => $item)
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-gray-700">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $pelayanan->prosedur }}</p>
                @endif
            </div>
            @endif

            <!-- Back Button -->
            <div class="flex gap-3">
                <a href="{{ route('admin.pelayanan.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Right Column (Sidebar Info) -->
        <div class="space-y-6">
            <!-- PIC Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-user-tie mr-2 text-blue-600"></i>
                    Penanggung Jawab
                </h2>
                <div class="space-y-3 text-sm">
                    @if($pelayanan->kontak_pic)
                        <div class="flex items-start">
                            <i class="fas fa-user text-gray-400 mt-1 mr-2 flex-shrink-0"></i>
                            <span class="text-gray-700">{{ $pelayanan->kontak_pic }}</span>
                        </div>
                    @endif
                    @if($pelayanan->email_pic)
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-gray-400 mt-1 mr-2 flex-shrink-0"></i>
                            <span class="text-gray-700 break-all">{{ $pelayanan->email_pic }}</span>
                        </div>
                    @endif
                    @if($pelayanan->telepon_pic)
                        <div class="flex items-start">
                            <i class="fas fa-phone text-gray-400 mt-1 mr-2 flex-shrink-0"></i>
                            <span class="text-gray-700">{{ $pelayanan->telepon_pic }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Audit Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-history mr-2 text-blue-600"></i>
                    Informasi Audit
                </h2>
                <div class="space-y-3 text-sm text-gray-600">
                    <div>
                        <h3 class="font-medium text-gray-900 mb-1">Dibuat</h3>
                        <p>{{ $pelayanan->created_at->format('d M Y H:i') }}</p>
                        @if($pelayanan->creator)
                            <p class="text-xs text-gray-500">oleh {{ $pelayanan->creator->name }}</p>
                        @endif
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <h3 class="font-medium text-gray-900 mb-1">Diperbarui</h3>
                        <p>{{ $pelayanan->updated_at->format('d M Y H:i') }}</p>
                        @if($pelayanan->updater)
                            <p class="text-xs text-gray-500">oleh {{ $pelayanan->updater->name }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function deletePelayanan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pelayanan ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = `{{ url('admin/pelayanan') }}/${id}`;
        form.submit();
    }
}
</script>
@endpush
@endsection
