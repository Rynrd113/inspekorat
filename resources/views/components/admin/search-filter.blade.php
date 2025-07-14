@props([
    'searchPlaceholder' => 'Cari...',
    'searchValue' => '',
    'searchName' => 'search',
    'filters' => [],
    'showExport' => false,
    'exportRoute' => null
])

<div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
    <div class="p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-{{ count($filters) > 0 ? (count($filters) + 1) : 1 }} gap-4">
                <!-- Search Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <x-search-input 
                        name="{{ $searchName }}"
                        placeholder="{{ $searchPlaceholder }}"
                        value="{{ $searchValue }}"
                        with-icon="true"
                        size="md"
                    />
                </div>

                <!-- Filter Fields -->
                @foreach($filters as $filter)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $filter['label'] }}</label>
                    @if($filter['type'] === 'select')
                    <select name="{{ $filter['name'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">{{ $filter['placeholder'] ?? 'Semua' }}</option>
                        @foreach($filter['options'] as $value => $label)
                        <option value="{{ $value }}" {{ request($filter['name']) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    @elseif($filter['type'] === 'date')
                    <input type="date" 
                           name="{{ $filter['name'] }}" 
                           value="{{ request($filter['name']) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <x-button type="submit" variant="primary" size="md">
                    <i class="fas fa-search mr-2"></i>Cari
                </x-button>
                
                <x-button 
                    type="button" 
                    variant="secondary" 
                    size="md"
                    onclick="window.location.href='{{ url()->current() }}'"
                >
                    <i class="fas fa-undo mr-2"></i>Reset
                </x-button>

                @if($showExport && $exportRoute)
                <x-button 
                    href="{{ $exportRoute }}"
                    variant="success" 
                    size="md"
                >
                    <i class="fas fa-download mr-2"></i>Export
                </x-button>
                @endif
            </div>
        </form>
    </div>
</div>