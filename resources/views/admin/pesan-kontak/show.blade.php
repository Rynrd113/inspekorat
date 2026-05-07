@extends('layouts.admin')
@section('title', 'Detail Pesan Kontak')
@section('main-content')

<div class="space-y-4">
    <div style="display:flex;align-items:center;gap:12px">
        <a href="{{ route('admin.pesan-kontak.index') }}" style="color:#6b7280;text-decoration:none"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-gray-900">Detail Pesan</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6" style="max-width:700px">
        <table style="width:100%;font-size:14px;border-collapse:collapse">
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280;width:140px">Tanggal</td>
                <td style="padding:10px 0;color:#111827">{{ $pesanKontak->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Nama</td>
                <td style="padding:10px 0;color:#111827;font-weight:500">{{ $pesanKontak->nama }}</td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Email</td>
                <td style="padding:10px 0">
                    <a href="mailto:{{ $pesanKontak->email }}" style="color:#2563eb">{{ $pesanKontak->email }}</a>
                </td>
            </tr>
            @if($pesanKontak->subjek)
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Subjek</td>
                <td style="padding:10px 0;color:#111827">{{ $pesanKontak->subjek }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding:10px 0;color:#6b7280;vertical-align:top">Pesan</td>
                <td style="padding:10px 0;color:#111827;line-height:1.7">{{ $pesanKontak->pesan }}</td>
            </tr>
        </table>

        <div style="margin-top:20px;display:flex;gap:8px">
            <a href="mailto:{{ $pesanKontak->email }}"
               style="padding:8px 16px;background:#2563eb;color:white;text-decoration:none;border-radius:6px;font-size:14px">
                <i class="fas fa-reply"></i> Balas via Email
            </a>
            <form method="POST" action="{{ route('admin.pesan-kontak.destroy', $pesanKontak->id) }}"
                  onsubmit="return confirm('Hapus pesan ini?')">
                @csrf @method('DELETE')
                <button type="submit" style="padding:8px 16px;background:#dc2626;color:white;border:none;border-radius:6px;font-size:14px;cursor:pointer">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
            <a href="{{ route('admin.pesan-kontak.index') }}"
               style="padding:8px 16px;border:1px solid #d1d5db;color:#374151;text-decoration:none;border-radius:6px;font-size:14px">
                Kembali
            </a>
        </div>
    </div>
</div>

@endsection
