@extends('layouts.admin')

@section('title', 'Detail FAQ')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detail FAQ</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
                <li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
                <li><a href="{{ route('admin.faq.index') }}" class="text-blue-600 hover:text-blue-800">FAQ</a></li>
                <li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
                <li class="text-gray-600">Detail</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h5 class="text-xl font-semibold text-gray-900 mb-0">
                        <i class="fas fa-question-circle mr-2 text-blue-600"></i>
                        Detail FAQ
                    </h5>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.faq.edit', $faq) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        <a href="{{ route('admin.faq.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50 w-48">Pertanyaan</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $faq->pertanyaan }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Jawaban</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{!! nl2br(e($faq->jawaban)) !!}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Kategori</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $faq->kategori ?? 'Umum' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Urutan</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $faq->urutan ?? 'Tidak diatur' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Status</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if($faq->status ?? true)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Dibuat</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $faq->created_at ? $faq->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                @if($faq->updated_at)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Terakhir Diupdate</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $faq->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50">
                    <a href="{{ route('admin.faq.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.faq.edit', $faq) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <i class="fas fa-edit mr-2"></i> Edit FAQ
                        </a>
                        <button type="button" onclick="confirmDelete({{ $faq->id }})" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" id="deleteModal" style="z-index: 1000;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Hapus</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus FAQ ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    document.getElementById('deleteForm').action = '{{ route("admin.faq.destroy", ":id") }}'.replace(':id', id);
    // Use a simple modal approach instead of Bootstrap
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'block';
    modal.classList.add('show');
}

// Close modal function
function closeModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
}

// Close when clicking backdrop
document.addEventListener('click', function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeModal();
    }
});
</script>
@endsection
