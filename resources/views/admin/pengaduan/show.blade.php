@extends('layouts.admin')
@section('title', 'Detail Pengaduan')
@section('main-content')

<div class="space-y-4">
    <div style="display:flex;align-items:center;gap:12px">
        <a href="/admin/pengaduan" style="color:#6b7280;text-decoration:none"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-gray-900">Detail Pengaduan</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6" style="max-width:800px">
        <table style="width:100%;font-size:14px;border-collapse:collapse">
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280;width:160px">Tanggal</td>
                <td style="padding:10px 0;color:#111827">{{ $pengaduan->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Nama Pengadu</td>
                <td style="padding:10px 0;color:#111827">{{ $pengaduan->nama_pengadu }}</td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Email</td>
                <td style="padding:10px 0;color:#111827">{{ $pengaduan->email }}</td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Telepon</td>
                <td style="padding:10px 0;color:#111827">{{ $pengaduan->telepon ?? '-' }}</td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Kategori</td>
                <td style="padding:10px 0;color:#111827">{{ $pengaduan->kategori ?? '-' }}</td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Subjek</td>
                <td style="padding:10px 0;color:#111827">{{ $pengaduan->subjek }}</td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280;vertical-align:top">Isi Pengaduan</td>
                <td style="padding:10px 0;color:#111827;line-height:1.6">{{ $pengaduan->isi_pengaduan }}</td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Status</td>
                <td style="padding:10px 0">
                    @if($pengaduan->status == 'pending')
                        <span style="padding:2px 8px;background:#fef3c7;color:#92400e;border-radius:9999px;font-size:12px">Pending</span>
                    @elseif($pengaduan->status == 'proses')
                        <span style="padding:2px 8px;background:#dbeafe;color:#1e40af;border-radius:9999px;font-size:12px">Proses</span>
                    @elseif($pengaduan->status == 'selesai')
                        <span style="padding:2px 8px;background:#d1fae5;color:#065f46;border-radius:9999px;font-size:12px">Selesai</span>
                    @else
                        <span style="padding:2px 8px;background:#f3f4f6;color:#374151;border-radius:9999px;font-size:12px">{{ $pengaduan->status }}</span>
                    @endif
                </td>
            </tr>
            @if($pengaduan->tanggapan)
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280;vertical-align:top">Tanggapan</td>
                <td style="padding:10px 0;color:#111827;line-height:1.6">{{ $pengaduan->tanggapan }}</td>
            </tr>
            @endif
        </table>

        <div style="margin-top:20px;display:flex;gap:8px">
            <a href="/admin/pengaduan/{{ $pengaduan->id }}/edit"
               style="padding:8px 16px;background:#2563eb;color:white;text-decoration:none;border-radius:6px;font-size:14px">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="/admin/pengaduan"
               style="padding:8px 16px;border:1px solid #d1d5db;color:#374151;text-decoration:none;border-radius:6px;font-size:14px">
                Kembali
            </a>
        </div>
    </div>
</div>

@endsection
