@extends('layouts.admin')

@section('title', 'Detail FAQ')

@section('header', 'Detail FAQ')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.faq.index') }}" class="text-blue-600 hover:text-blue-800">FAQ</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Detail</li>
@endsection

@section('main-content')
<div class="space-y-6">

    <x-card>
        <x-slot:header>
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold text-gray-900 mb-0">
                    <i class="fas fa-question-circle mr-2 text-blue-600"></i>
                    Detail FAQ
                </h5>
                <div class="flex space-x-2">
                    <x-button href="{{ route('admin.faq.edit', $faq) }}" variant="warning" size="md">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </x-button>
                    <x-button href="{{ route('admin.faq.index') }}" variant="secondary" size="md">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </x-button>
                </div>
            </div>
        </x-slot:header>
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
                                        <x-badge variant="primary" size="md">
                                            {{ $faq->kategori ?? 'Umum' }}
                                        </x-badge>
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
                                            <x-badge variant="success" size="md">Aktif</x-badge>
                                        @else
                                            <x-badge variant="danger" size="md">Tidak Aktif</x-badge>
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
        <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200 bg-gray-50 p-4 rounded-lg">
            <x-button href="{{ route('admin.faq.index') }}" variant="secondary" size="md">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </x-button>
            <div class="flex space-x-2">
                <x-button href="{{ route('admin.faq.edit', $faq) }}" variant="warning" size="md">
                    <i class="fas fa-edit mr-2"></i> Edit FAQ
                </x-button>
                <x-button type="button" onclick="confirmDelete({{ $faq->id }})" variant="danger" size="md">
                    <i class="fas fa-trash mr-2"></i> Hapus
                </x-button>
            </div>
        </div>
    </x-card>
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
                    <x-button type="submit" variant="danger" size="md" class="sm:ml-3">
                        Hapus
                    </x-button>
                </form>
                <x-button type="button" onclick="closeModal()" variant="secondary" size="md" class="mt-3 sm:mt-0">
                    Batal
                </x-button>
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
