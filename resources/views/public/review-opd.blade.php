@extends('layouts.main')

@section('title', 'Daftar Review OPD - Inspektorat Papua Tengah')
@section('description', 'Daftar pelaksanaan review Organisasi Perangkat Daerah oleh Inspektorat Papua Tengah.')

@section('content')
<div class="min-h-screen bg-gray-50">

    <x-hero-section
        title="Daftar Review OPD"
        description="Informasi pelaksanaan review Organisasi Perangkat Daerah oleh Inspektorat Papua Tengah"
        icon="fas fa-clipboard-check"
    />

    <x-breadcrumb :items="['Informasi', 'Daftar Review OPD']" />

    {{-- Filter --}}
    <section class="bg-white py-4 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('public.review-opd') }}" class="flex flex-wrap gap-3 items-center">
                <div class="relative flex-1" style="min-width:200px">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama OPD..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                </div>
                <select name="tahun"
                        class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ request('tahun')==$t ? 'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>
                <select name="status"
                        class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    <option value="">Semua Status</option>
                    <option value="dijadwalkan"     {{ request('status')=='dijadwalkan'     ? 'selected':'' }}>Dijadwalkan</option>
                    <option value="sedang_berjalan" {{ request('status')=='sedang_berjalan' ? 'selected':'' }}>Sedang Berjalan</option>
                    <option value="selesai"         {{ request('status')=='selesai'         ? 'selected':'' }}>Selesai</option>
                </select>
                <button type="submit"
                        class="px-5 py-2.5 bg-blue-700 text-white text-sm font-medium rounded-xl hover:bg-blue-800 transition-colors">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                @if(request('search') || request('status') || request('tahun'))
                <a href="{{ route('public.review-opd') }}"
                   class="px-4 py-2.5 border border-gray-300 text-gray-600 text-sm rounded-xl hover:bg-gray-50 transition-colors">
                    Reset
                </a>
                @endif
            </form>
        </div>
    </section>

    {{-- Konten --}}
    <section class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Ringkasan --}}
            @php
                $baseQuery = \App\Models\ReviewOpd::query();
                if(request('tahun')) $baseQuery->where('tahun_anggaran', request('tahun'));
                $totalDijadwalkan = (clone $baseQuery)->where('status_review', 'dijadwalkan')->count();
                $totalBerjalan    = (clone $baseQuery)->where('status_review', 'sedang_berjalan')->count();
                $totalSelesai     = (clone $baseQuery)->where('status_review', 'selesai')->count();
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-yellow-400 p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-default">
                    <div class="bg-yellow-100 rounded-full p-2.5 flex-shrink-0">
                        <i class="fas fa-calendar-alt text-yellow-600 text-lg"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalDijadwalkan }}</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Dijadwalkan</div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-400 p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-default">
                    <div class="bg-blue-100 rounded-full p-2.5 flex-shrink-0">
                        <i class="fas fa-spinner text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalBerjalan }}</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Sedang Berjalan</div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-green-400 p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-default">
                    <div class="bg-green-100 rounded-full p-2.5 flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalSelesai }}</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Selesai</div>
                    </div>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                @if($reviews->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-14">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama OPD</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tahun</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Review</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hasil Review</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($reviews as $i => $r)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-400">{{ $reviews->firstItem() + $i }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    <a href="{{ route('public.review-opd.show', $r->id) }}"
                                       class="hover:text-blue-600 transition-colors">
                                        {{ $r->nama_opd }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $r->tahun_anggaran }}</td>
                                <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                    {{ $r->tanggal_review->format('d/m/Y') }}
                                    @if($r->tanggal_selesai)
                                    <span class="text-gray-400"> – {{ $r->tanggal_selesai->format('d/m/Y') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($r->status_review == 'dijadwalkan')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Dijadwalkan</span>
                                    @elseif($r->status_review == 'sedang_berjalan')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Sedang Berjalan</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $r->hasil_review ?: '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($r->dokumen_path)
                                        <a href="{{ Storage::url($r->dokumen_path) }}" target="_blank"
                                           class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs font-medium transition-colors">
                                            <i class="fas fa-file-pdf mr-1 text-red-500"></i> Unduh
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $reviews->links() }}
                </div>
                @else
                <div class="text-center py-10">
                    <i class="fas fa-clipboard-list text-3xl text-gray-300 mb-2 block"></i>
                    <p class="text-gray-500 text-sm font-medium">Belum ada data review OPD.</p>
                    @if(request('search') || request('status') || request('tahun'))
                        <a href="{{ route('public.review-opd') }}" class="mt-2 inline-block text-blue-600 hover:underline text-sm">Lihat semua</a>
                    @endif
                </div>
                @endif
            </div>

        </div>
    </section>

</div>
@endsection
