@props([
    'title' => '',
    'id' => 'confirmModal',
    'size' => 'md',
    'type' => 'danger',
    'confirmText' => 'Hapus',
    'cancelText' => 'Batal',
    'confirmAction' => '',
    'description' => 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.'
])

@php
$sizes = [
    'sm' => 'max-w-md',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
];

$types = [
    'danger' => [
        'icon' => 'fas fa-exclamation-triangle text-red-600',
        'button' => 'bg-red-600 hover:bg-red-700 text-white'
    ],
    'warning' => [
        'icon' => 'fas fa-exclamation-triangle text-yellow-600',
        'button' => 'bg-yellow-600 hover:bg-yellow-700 text-white'
    ],
    'info' => [
        'icon' => 'fas fa-info-circle text-blue-600',
        'button' => 'bg-blue-600 hover:bg-blue-700 text-white'
    ],
];

$sizeClass = $sizes[$size] ?? $sizes['md'];
$typeConfig = $types[$type] ?? $types['danger'];
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle {{ $sizeClass }} sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="{{ $typeConfig['icon'] }} text-xl"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            {{ $title }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                {{ $description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        onclick="{{ $confirmAction }}"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 {{ $typeConfig['button'] }} text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ $confirmText }}
                </button>
                <button type="button" 
                        onclick="closeModal('{{ $id }}')"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ $cancelText }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>
