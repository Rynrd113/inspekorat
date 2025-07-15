@props([
    'title' => '',
    'breadcrumbs' => [],
    'actions' => null,
    'description' => null,
    'showFilters' => false,
    'filters' => [],
    'searchPlaceholder' => 'Cari...',
    'showExport' => false,
    'exportRoute' => null,
    'showStats' => false,
    'stats' => []
])

<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header 
        :title="$title"
        :breadcrumbs="$breadcrumbs"
        :show-actions="true"
    >
        <x-slot name="actions">
            {{ $actions }}
        </x-slot>
        
        @if($description)
        <p class="text-gray-600 mt-2">{{ $description }}</p>
        @endif
    </x-admin.page-header>

    <!-- Stats Cards -->
    @if($showStats && count($stats) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($stats as $stat)
        <x-admin.stats-card 
            :title="$stat['title']"
            :value="$stat['value']"
            :icon="$stat['icon']"
            :color="$stat['color']"
            :url="$stat['url'] ?? null"
            :description="$stat['description'] ?? null"
        />
        @endforeach
    </div>
    @endif

    <!-- Search & Filter -->
    @if($showFilters)
    <x-admin.search-filter 
        :search-placeholder="$searchPlaceholder"
        :search-value="request('search')"
        :filters="$filters"
        :show-export="$showExport"
        :export-route="$exportRoute"
    />
    @endif

    <!-- Main Content -->
    <div class="space-y-6">
        {{ $slot }}
    </div>
</div>
