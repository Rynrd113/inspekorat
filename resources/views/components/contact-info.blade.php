@props(['variant' => 'card'])

@php
    $contact = config('contact');
    $items = [
        [
            'icon' => 'fas fa-map-marker-alt',
            'color' => 'blue',
            'title' => 'Alamat Kantor',
            'content' => $contact['alamat'],
            'type' => 'text'
        ],
        [
            'icon' => 'fab fa-instagram',
            'color' => 'pink',
            'title' => 'Instagram',
            'content' => $contact['instagram']['handle'],
            'url' => $contact['instagram']['url'],
            'type' => 'link'
        ],
        [
            'icon' => 'fas fa-envelope',
            'color' => 'purple',
            'title' => 'Email',
            'content' => $contact['email'],
            'url' => 'mailto:' . $contact['email'],
            'type' => 'link'
        ],
        [
            'icon' => 'fas fa-clock',
            'color' => 'orange',
            'title' => 'Jam Operasional',
            'content' => $contact['jam_operasional'],
            'type' => 'text'
        ],
        [
            'icon' => 'fas fa-globe',
            'color' => 'teal',
            'title' => 'Website',
            'content' => $contact['website']['display'],
            'url' => $contact['website']['url'],
            'type' => 'link'
        ],
        [
            'icon' => 'fas fa-map',
            'color' => 'red',
            'title' => 'Lokasi',
            'content' => $contact['lokasi']['text'],
            'url' => $contact['lokasi']['maps_url'],
            'type' => 'link',
            'external_icon' => true
        ],
    ];
@endphp

@if($variant === 'card')
    {{-- Card variant (untuk homepage) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($items as $item)
        <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-{{ $item['color'] }}-500 to-{{ $item['color'] }}-600 rounded-2xl mb-6">
                <i class="{{ $item['icon'] }} text-white text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $item['title'] }}</h3>
            <div class="text-gray-600 text-lg {{ $item['title'] === 'Alamat Kantor' || $item['title'] === 'Jam Operasional' ? 'leading-relaxed' : '' }}">
                @if($item['type'] === 'link')
                    <a href="{{ $item['url'] }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors">
                        @if($item['external_icon'] ?? false)
                            <i class="fas fa-external-link-alt mr-2"></i>
                        @endif
                        {{ $item['content'] }}
                    </a>
                @else
                    {{ $item['content'] }}
                @endif
            </div>
        </div>
        @endforeach
    </div>
@else
    {{-- List variant (untuk profil) --}}
    <div class="space-y-4">
        @foreach($items as $item)
        <div class="flex items-{{ $item['title'] === 'Alamat Kantor' ? 'start' : 'center' }} space-x-3">
            <div class="w-8 h-8 bg-{{ $item['color'] }}-100 rounded-full flex items-center justify-center {{ $item['title'] === 'Alamat Kantor' ? 'mt-1' : '' }}">
                <i class="{{ $item['icon'] }} text-{{ $item['color'] }}-600 text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900">{{ $item['title'] }}</p>
                @if($item['type'] === 'link')
                    <a href="{{ $item['url'] }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $item['content'] }}</a>
                @else
                    <p class="text-gray-600">{{ $item['content'] }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif
