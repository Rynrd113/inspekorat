@props([
    'columns' => [],
    'rows' => [],
    'actions' => [],
    'showActions' => true,
    'emptyMessage' => 'Tidak ada data yang tersedia',
    'emptyIcon' => 'fas fa-inbox'
])

<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
    @if($rows->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach($columns as $column)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $column['label'] }}
                    </th>
                    @endforeach
                    @if($showActions)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($rows as $row)
                <tr class="hover:bg-gray-50 transition-colors">
                    @foreach($columns as $column)
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if(isset($column['render']))
                            {!! $column['render']($row) !!}
                        @else
                            {{ data_get($row, $column['key']) }}
                        @endif
                    </td>
                    @endforeach
                    @if($showActions)
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            @foreach($actions as $action)
                                @if(isset($action['condition']) && !$action['condition']($row))
                                    @continue
                                @endif
                                
                                @if($action['type'] === 'link')
                                <a href="{{ $action['url']($row) }}" 
                                   class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md {{ $action['class'] ?? 'text-blue-600 hover:text-blue-800' }}"
                                   title="{{ $action['title'] ?? '' }}">
                                    <i class="{{ $action['icon'] }} mr-1"></i>
                                    {{ $action['label'] }}
                                </a>
                                @elseif($action['type'] === 'button')
                                <button 
                                    type="button"
                                    onclick="{{ $action['onclick']($row) }}"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md {{ $action['class'] ?? 'text-gray-600 hover:text-gray-800' }}"
                                    title="{{ $action['title'] ?? '' }}">
                                    <i class="{{ $action['icon'] }} mr-1"></i>
                                    {{ $action['label'] }}
                                </button>
                                @elseif($action['type'] === 'form')
                                <form method="POST" action="{{ $action['url']($row) }}" class="inline">
                                    @csrf
                                    @method($action['method'] ?? 'DELETE')
                                    <button 
                                        type="submit"
                                        onclick="return confirm('{{ $action['confirm'] ?? 'Apakah Anda yakin?' }}')"
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md {{ $action['class'] ?? 'text-red-600 hover:text-red-800' }}"
                                        title="{{ $action['title'] ?? '' }}">
                                        <i class="{{ $action['icon'] }} mr-1"></i>
                                        {{ $action['label'] }}
                                    </button>
                                </form>
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
    @else
    <!-- Empty State -->
    <div class="text-center py-12">
        <div class="text-gray-400 mb-4">
            <i class="{{ $emptyIcon }} text-4xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $emptyMessage }}</h3>
        <p class="text-gray-600">Belum ada data yang tersedia.</p>
    </div>
    @endif
</div>