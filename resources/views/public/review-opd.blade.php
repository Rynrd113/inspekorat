@extends('layouts.main')

@section('title', 'Daftar Review OPD - Inspektorat Papua Tengah')
@section('description', 'Daftar pelaksanaan review Organisasi Perangkat Daerah oleh Inspektorat Papua Tengah.')

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Compact page header --}}
    <div class="bg-blue-800 text-white py-4 border-b border-blue-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <i class="fas fa-clipboard-check text-blue-300 text-lg flex-shrink-0"></i>
                <div>
                    <h1 class="text-lg font-bold leading-tight">Daftar Review OPD</h1>
                    <nav class="text-xs text-blue-300 mt-0.5">
                        <a href="{{ route('public.index') }}" class="hover:text-white transition-colors">Beranda</a>
                        <span class="mx-1">/</span>
                        <span>Informasi</span>
                        <span class="mx-1">/</span>
                        <span class="text-white">Daftar Review OPD</span>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="bg-white border-b border-gray-200 py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('public.review-opd') }}" class="flex flex-wrap gap-2 items-center">
                <div class="relative flex-1" style="min-width:180px">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama OPD..."
                           class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-xs"></i>
                    </div>
                </div>
                <select name="tahun"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ request('tahun')==$t ? 'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>
                <select name="status"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="dijadwalkan"     {{ request('status')=='dijadwalkan'     ? 'selected':'' }}>Dijadwalkan</option>
                    <option value="sedang_berjalan" {{ request('status')=='sedang_berjalan' ? 'selected':'' }}>Sedang Berjalan</option>
                    <option value="selesai"         {{ request('status')=='selesai'         ? 'selected':'' }}>Selesai</option>
                </select>
                <button type="submit"
                        class="px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded-lg hover:bg-blue-800 transition-colors">
                    <i class="fas fa-search mr-1 text-xs"></i> Cari
                </button>
                @if(request('search') || request('status') || request('tahun'))
                <a href="{{ route('public.review-opd') }}"
                   class="px-3 py-2 border border-gray-300 text-gray-500 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                    Reset
                </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Konten --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">

        {{-- Statistik ringkas --}}
        @php
            $baseQuery = \App\Models\ReviewOpd::query();
            if(request('tahun')) $baseQuery->where('tahun_anggaran', request('tahun'));
            $totalDijadwalkan = (clone $baseQuery)->where('status_review', 'dijadwalkan')->count();
            $totalBerjalan    = (clone $baseQuery)->where('status_review', 'sedang_berjalan')->count();
            $totalSelesai     = (clone $baseQuery)->where('status_review', 'selesai')->count();
        @endphp
        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="bg-white rounded-lg border border-l-4 border-yellow-400 px-4 py-3 flex items-center gap-3">
                <i class="fas fa-calendar-alt text-yellow-500 text-base flex-shrink-0"></i>
                <div>
                    <div class="text-xl font-bold text-gray-900 leading-none">{{ $totalDijadwalkan }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Dijadwalkan</div>
                </div>
            </div>
            <div class="bg-white rounded-lg border border-l-4 border-blue-400 px-4 py-3 flex items-center gap-3">
                <i class="fas fa-spinner text-blue-500 text-base flex-shrink-0"></i>
                <div>
                    <div class="text-xl font-bold text-gray-900 leading-none">{{ $totalBerjalan }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Sedang Berjalan</div>
                </div>
            </div>
            <div class="bg-white rounded-lg border border-l-4 border-green-400 px-4 py-3 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500 text-base flex-shrink-0"></i>
                <div>
                    <div class="text-xl font-bold text-gray-900 leading-none">{{ $totalSelesai }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Selesai</div>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
            @if($reviews->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">No</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama OPD</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tahun</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Review</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hasil</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dok.</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($reviews as $i => $r)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $reviews->firstItem() + $i }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">
                                <a href="{{ route('public.review-opd.show', $r->id) }}"
                                   class="hover:text-blue-600 transition-colors">
                                    {{ $r->nama_opd }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap text-xs">{{ $r->tahun_anggaran }}</td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap text-xs">
                                {{ $r->tanggal_review->format('d/m/Y') }}
                                @if($r->tanggal_selesai)
                                <span class="text-gray-400"> – {{ $r->tanggal_selesai->format('d/m/Y') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($r->status_review == 'dijadwalkan')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Dijadwalkan</span>
                                @elseif($r->status_review == 'sedang_berjalan')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Berjalan</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Selesai</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ Str::limit($r->hasil_review, 40) ?: '-' }}</td>
                            <td class="px-4 py-3">
                                @if($r->dokumen_path)
                                    <a href="{{ Storage::url($r->dokumen_path) }}" target="_blank"
                                       class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs transition-colors">
                                        <i class="fas fa-file-pdf mr-1 text-red-400"></i> Unduh
                                    </a>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $reviews->links() }}
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-clipboard-list text-2xl text-gray-300 mb-2 block"></i>
                <p class="text-gray-400 text-sm">Belum ada data review OPD.</p>
                @if(request('search') || request('status') || request('tahun'))
                    <a href="{{ route('public.review-opd') }}" class="mt-1 inline-block text-blue-600 hover:underline text-xs">Lihat semua</a>
                @endif
            </div>
            @endif
        </div>

    </div>

</div>
@endsection
