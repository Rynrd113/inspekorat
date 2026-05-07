@extends('layouts.admin')

@section('title', 'Statistik Persetujuan')
@section('header', 'Statistik Persetujuan')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.approvals.index') }}" class="text-blue-600 hover:text-blue-800">Persetujuan</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Statistik</li>
@endsection

@section('main-content')
<div class="space-y-6">

    {{-- Kartu Ringkasan --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
        <div class="bg-white rounded-lg shadow p-6" style="border-left:4px solid #f59e0b">
            <div style="font-size:13px;color:#6b7280;margin-bottom:4px">Menunggu</div>
            <div style="font-size:32px;font-weight:700;color:#92400e">{{ $stats['total_pending'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6" style="border-left:4px solid #10b981">
            <div style="font-size:13px;color:#6b7280;margin-bottom:4px">Disetujui</div>
            <div style="font-size:32px;font-weight:700;color:#065f46">{{ $stats['total_approved'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6" style="border-left:4px solid #ef4444">
            <div style="font-size:13px;color:#6b7280;margin-bottom:4px">Ditolak</div>
            <div style="font-size:32px;font-weight:700;color:#991b1b">{{ $stats['total_rejected'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6" style="border-left:4px solid #3b82f6">
            <div style="font-size:13px;color:#6b7280;margin-bottom:4px">Tingkat Persetujuan</div>
            <div style="font-size:32px;font-weight:700;color:#1e40af">{{ number_format($stats['approval_rate'], 1) }}%</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6" style="border-left:4px solid #8b5cf6">
            <div style="font-size:13px;color:#6b7280;margin-bottom:4px">Rata-rata Proses</div>
            <div style="font-size:32px;font-weight:700;color:#5b21b6">{{ number_format($stats['avg_processing_time'], 1) }} <span style="font-size:14px;font-weight:400">jam</span></div>
        </div>
    </div>

    {{-- Pending per Tipe --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 style="font-size:16px;font-weight:600;color:#111827;margin-bottom:16px">Menunggu per Tipe Konten</h3>
        @if($stats['pending_by_type']->count() > 0)
        <table style="width:100%;border-collapse:collapse;font-size:14px">
            <thead>
                <tr style="background:#f9fafb">
                    <th style="padding:10px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Tipe Konten</th>
                    <th style="padding:10px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['pending_by_type'] as $item)
                <tr style="border-top:1px solid #e5e7eb">
                    <td style="padding:10px 16px;color:#111827">{{ class_basename($item->model_type) }}</td>
                    <td style="padding:10px 16px">
                        <span style="padding:2px 10px;background:#fef3c7;color:#92400e;border-radius:9999px;font-size:12px">{{ $item->count }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color:#6b7280;font-size:14px">Tidak ada konten yang menunggu persetujuan.</p>
        @endif
    </div>

    <div>
        <a href="{{ route('admin.approvals.index') }}"
           style="padding:8px 16px;border:1px solid #d1d5db;color:#374151;text-decoration:none;border-radius:6px;font-size:14px">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
    </div>

</div>
@endsection
