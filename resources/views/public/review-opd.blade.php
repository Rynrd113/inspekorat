@extends('layouts.public')

@section('title', 'Daftar Review OPD - Inspektorat Papua Tengah')

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Hero --}}
    <div class="bg-gradient-to-r from-blue-700 to-indigo-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4">
                <i class="fas fa-clipboard-check text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-3">Daftar Review OPD</h1>
            <p class="text-blue-100 text-lg">Informasi pelaksanaan review Organisasi Perangkat Daerah oleh Inspektorat Papua Tengah</p>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex text-sm">
                <a href="{{ route('public.index') }}" class="text-gray-500 hover:text-blue-600">
                    <i class="fas fa-home mr-1"></i> Beranda
                </a>
                <i class="fas fa-chevron-right text-gray-400 mx-2 mt-0.5"></i>
                <span class="text-gray-900 font-medium">Daftar Review OPD</span>
            </nav>
        </div>
    </div>

    {{-- Ringkasan Status --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            @php
                $totalDijadwalkan   = \App\Models\ReviewOpd::where('status_review', 'dijadwalkan')->count();
                $totalBerjalan      = \App\Models\ReviewOpd::where('status_review', 'sedang_berjalan')->count();
                $totalSelesai       = \App\Models\ReviewOpd::where('status_review', 'selesai')->count();
            @endphp
            <div class="bg-white rounded-lg shadow-sm border-l-4 border-yellow-400 p-5 flex items-center gap-4">
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-calendar-alt text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalDijadwalkan }}</div>
                    <div class="text-sm text-gray-500">Dijadwalkan</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border-l-4 border-blue-400 p-5 flex items-center gap-4">
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-spinner text-blue-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalBerjalan }}</div>
                    <div class="text-sm text-gray-500">Sedang Berjalan</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border-l-4 border-green-400 p-5 flex items-center gap-4">
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalSelesai }}</div>
                    <div class="text-sm text-gray-500">Selesai</div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('public.review-opd') }}" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama OPD..."
                       class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <select name="status"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="dijadwalkan"     {{ request('status')=='dijadwalkan'     ? 'selected':'' }}>Dijadwalkan</option>
                    <option value="sedang_berjalan" {{ request('status')=='sedang_berjalan' ? 'selected':'' }}>Sedang Berjalan</option>
                    <option value="selesai"         {{ request('status')=='selesai'         ? 'selected':'' }}>Selesai</option>
                </select>
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                @if(request('search') || request('status'))
                <a href="{{ route('public.review-opd') }}"
                   class="px-4 py-2 border border-gray-300 text-gray-600 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                    Reset
                </a>
                @endif
            </form>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if($reviews->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blue-700 text-white">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold w-14">No</th>
                            <th class="px-5 py-3 text-left font-semibold">Nama OPD</th>
                            <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Tanggal Review</th>
                            <th class="px-5 py-3 text-left font-semibold">Status Review</th>
                            <th class="px-5 py-3 text-left font-semibold">Hasil Review</th>
                            <th class="px-5 py-3 text-left font-semibold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($reviews as $i => $r)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4 text-gray-500">{{ $reviews->firstItem() + $i }}</td>
                            <td class="px-5 py-4 font-medium text-gray-900">{{ $r->nama_opd }}</td>
                            <td class="px-5 py-4 text-gray-600 whitespace-nowrap">{{ $r->tanggal_review->format('d/m/Y') }}</td>
                            <td class="px-5 py-4">
                                @if($r->status_review == 'dijadwalkan')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                        <i class="fas fa-calendar-alt"></i> Dijadwalkan
                                    </span>
                                @elseif($r->status_review == 'sedang_berjalan')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        <i class="fas fa-spinner"></i> Sedang Berjalan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        <i class="fas fa-check-circle"></i> Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $r->hasil_review ?: '-' }}</td>
                            <td class="px-5 py-4 text-gray-500 max-w-xs">{{ $r->keterangan ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $reviews->links() }}
            </div>
            @else
            <div class="text-center py-16 text-gray-500">
                <i class="fas fa-clipboard-list text-5xl text-gray-300 mb-4 block"></i>
                <p class="font-medium">Belum ada data review OPD.</p>
                @if(request('search') || request('status'))
                    <a href="{{ route('public.review-opd') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">Lihat semua</a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
