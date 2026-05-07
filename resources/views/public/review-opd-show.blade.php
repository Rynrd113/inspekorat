@extends('layouts.main')

@section('title', $reviewOpd->nama_opd . ' - Review OPD - Inspektorat Papua Tengah')
@section('description', 'Detail pelaksanaan review ' . $reviewOpd->nama_opd . ' tahun ' . $reviewOpd->tahun_anggaran . ' oleh Inspektorat Papua Tengah.')

@section('content')
<div class="min-h-screen bg-gray-50">

    <x-hero-section
        title="Detail Review OPD"
        description="Informasi lengkap pelaksanaan review Organisasi Perangkat Daerah"
        icon="fas fa-clipboard-check"
    />

    <x-breadcrumb :items="['Informasi', 'Daftar Review OPD', $reviewOpd->nama_opd]" />

    <section class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Status badge --}}
            <div class="mb-6 flex items-center gap-3">
                @if($reviewOpd->status_review == 'dijadwalkan')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-calendar-alt mr-1.5"></i> Dijadwalkan
                    </span>
                @elseif($reviewOpd->status_review == 'sedang_berjalan')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-spinner mr-1.5"></i> Sedang Berjalan
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1.5"></i> Selesai
                    </span>
                @endif
                <span class="text-sm text-gray-500">Tahun Anggaran {{ $reviewOpd->tahun_anggaran }}</span>
            </div>

            {{-- Card utama --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h1 class="text-xl font-bold text-gray-900">{{ $reviewOpd->nama_opd }}</h1>
                </div>

                <dl class="divide-y divide-gray-100">
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Mulai Review</dt>
                        <dd class="text-sm text-gray-900 col-span-2">{{ $reviewOpd->tanggal_review->format('d F Y') }}</dd>
                    </div>
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                        <dd class="text-sm text-gray-900 col-span-2">
                            {{ $reviewOpd->tanggal_selesai ? $reviewOpd->tanggal_selesai->format('d F Y') : '-' }}
                        </dd>
                    </div>
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Status Review</dt>
                        <dd class="text-sm text-gray-900 col-span-2">
                            {{ \App\Models\ReviewOpd::$statusLabels[$reviewOpd->status_review] }}
                        </dd>
                    </div>
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Hasil Review</dt>
                        <dd class="text-sm text-gray-900 col-span-2">{{ $reviewOpd->hasil_review ?: '-' }}</dd>
                    </div>
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                        <dd class="text-sm text-gray-900 col-span-2 whitespace-pre-line">
                            {{ $reviewOpd->keterangan ?: '-' }}
                        </dd>
                    </div>
                    @if($reviewOpd->dokumen_path)
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Dokumen</dt>
                        <dd class="text-sm col-span-2">
                            <a href="{{ Storage::url($reviewOpd->dokumen_path) }}" target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-file-pdf text-red-200"></i>
                                Unduh Dokumen Hasil Review
                            </a>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <div class="mt-6">
                <a href="{{ route('public.review-opd') }}"
                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Review OPD
                </a>
            </div>

        </div>
    </section>

</div>
@endsection
