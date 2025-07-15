@props([
    'columns' => [],
    'rows' => [],
    'actions' => [],
    'showActions' => true,
    'emptyMessage' => 'Tidak ada data yang tersedia',
    'emptyIcon' => 'fas fa-inbox',
    'showPagination' => true,
    'striped' => false,
    'hover' => true,
    'compact' => false
])

<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
    @if($rows->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach($columns as $column)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ $column['class'] ?? '' }}">
                        @if(isset($column['sortable']) && $column['sortable'])
                            <button class="flex items-center space-x-1 hover:text-gray-700 focus:outline-none">
                                <span>{{ $column['label'] }}</span>
                                <i class="fas fa-sort text-gray-400"></i>
                            </button>
                        @else
                            {{ $column['label'] }}
                        @endif
                    </th>
                    @endforeach
                    @if($showActions && count($actions) > 0)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($rows as $row)
                <tr class="{{ $striped && $loop->odd ? 'bg-gray-50' : '' }} {{ $hover ? 'hover:bg-gray-50' : '' }} transition-colors" 
                    @if(isset($row->id)) data-id="{{ $row->id }}" @endif>
                    @foreach($columns as $column)
                    <td class="px-6 py-4 {{ $compact ? 'py-2' : 'py-4' }} whitespace-nowrap text-sm text-gray-900 {{ $column['class'] ?? '' }}">
                        @if(isset($column['render']))
                            {!! $column['render'] !!}
                        @else
                            @php
                                $value = data_get($row, $column['key']);
                                if (is_array($value) || is_object($value)) {
                                    $value = json_encode($value);
                                }
                                // Ensure value is always a string for display
                                $value = (string) $value;
                            @endphp
                            {{ $value }}
                        @endif
                    </td>
                    @endforeach
                    @if($showActions && count($actions) > 0)
                    <td class="px-6 py-4 {{ $compact ? 'py-2' : 'py-4' }} whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            @foreach($actions as $action)
                                @if(isset($action['condition']) && !$action['condition']($row))
                                    @continue
                                @endif
                                
                                @if($action['type'] === 'link')
                                <a href="{{ $action['url']($row) }}" 
                                   class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md {{ $action['class'] ?? 'text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100' }}"
                                   title="{{ $action['label'] }}">
                                    @if(isset($action['icon']))
                                        <i class="{{ $action['icon'] }} {{ isset($action['label']) ? 'mr-1' : '' }}"></i>
                                    @endif
                                    {{ $action['label'] ?? '' }}
                                </a>
                                @elseif($action['type'] === 'button')
                                <button type="button" 
                                        onclick="{{ $action['onclick'] }}"
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md {{ $action['class'] ?? 'text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100' }}"
                                        title="{{ $action['label'] }}">
                                    @if(isset($action['icon']))
                                        <i class="{{ $action['icon'] }} {{ isset($action['label']) ? 'mr-1' : '' }}"></i>
                                    @endif
                                    {{ $action['label'] ?? '' }}
                                </button>
                                @endif
                            @endforeach
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($showPagination && method_exists($rows, 'links'))
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $rows->links() }}
    </div>
    @endif
    
    @else
    <!-- Empty State -->
    <div class="text-center py-12">
        <i class="{{ $emptyIcon }} text-4xl text-gray-400 mb-4"></i>
        <p class="text-gray-500 text-lg">{{ $emptyMessage }}</p>
    </div>
    @endif
</div>
