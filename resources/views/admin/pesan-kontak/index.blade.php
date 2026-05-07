@extends('layouts.admin')
@section('title', 'Pesan Kontak')
@section('main-content')

<div class="space-y-4">
    <div style="display:flex;justify-content:space-between;align-items:center">
        <h1 class="text-2xl font-bold text-gray-900">
            Pesan Kontak
            @if($belumDibaca > 0)
                <span style="background:#dc2626;color:white;border-radius:9999px;font-size:13px;padding:2px 8px;margin-left:6px">{{ $belumDibaca }} baru</span>
            @endif
        </h1>
    </div>

    @if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:6px;padding:10px 14px;color:#065f46;font-size:14px">
        {{ session('success') }}
    </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.pesan-kontak.index') }}" style="display:flex;gap:8px;flex-wrap:wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / email / subjek..."
                   style="padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;width:260px">
            <select name="status" style="padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px">
                <option value="">Semua</option>
                <option value="belum" {{ request('status')=='belum' ? 'selected' : '' }}>Belum Dibaca</option>
                <option value="dibaca" {{ request('status')=='dibaca' ? 'selected' : '' }}>Sudah Dibaca</option>
            </select>
            <button type="submit" style="padding:6px 16px;background:#2563eb;color:white;border:none;border-radius:6px;cursor:pointer">Filter</button>
            <a href="{{ route('admin.pesan-kontak.index') }}" style="padding:6px 16px;border:1px solid #d1d5db;border-radius:6px;text-decoration:none;color:#374151;font-size:14px">Reset</a>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($pesans->count() > 0)
        <table style="width:100%;border-collapse:collapse;font-size:14px">
            <thead style="background:#f9fafb">
                <tr>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Tanggal</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Pengirim</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Subjek</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Status</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesans as $p)
                <tr style="border-top:1px solid #e5e7eb;{{ !$p->sudah_dibaca ? 'background:#eff6ff' : '' }}">
                    <td style="padding:12px 16px;color:#6b7280;white-space:nowrap">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    <td style="padding:12px 16px">
                        <div style="font-weight:{{ !$p->sudah_dibaca ? '600' : '400' }};color:#111827">{{ $p->nama }}</div>
                        <div style="font-size:12px;color:#6b7280">{{ $p->email }}</div>
                    </td>
                    <td style="padding:12px 16px;color:#374151;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        {{ $p->subjek ?: '(tanpa subjek)' }}
                    </td>
                    <td style="padding:12px 16px">
                        @if(!$p->sudah_dibaca)
                            <span style="padding:2px 8px;background:#dbeafe;color:#1e40af;border-radius:9999px;font-size:12px">Baru</span>
                        @else
                            <span style="padding:2px 8px;background:#f3f4f6;color:#6b7280;border-radius:9999px;font-size:12px">Dibaca</span>
                        @endif
                    </td>
                    <td style="padding:12px 16px;white-space:nowrap">
                        <a href="{{ route('admin.pesan-kontak.show', $p->id) }}" style="color:#2563eb;margin-right:10px" title="Lihat"><i class="fas fa-eye"></i></a>
                        <form method="POST" action="{{ route('admin.pesan-kontak.destroy', $p->id) }}" style="display:inline"
                              onsubmit="return confirm('Hapus pesan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#dc2626;cursor:pointer" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:12px 16px">
            {{ $pesans->links() }}
        </div>
        @else
        <div style="text-align:center;padding:48px;color:#6b7280">
            <i class="fas fa-envelope-open" style="font-size:32px;margin-bottom:12px;display:block;color:#d1d5db"></i>
            <p>Belum ada pesan masuk.</p>
        </div>
        @endif
    </div>
</div>

@endsection
