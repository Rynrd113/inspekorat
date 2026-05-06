@extends('layouts.admin')
@section('title', 'Kelola Pengaduan')
@section('main-content')

<div class="space-y-4">
    <h1 class="text-2xl font-bold text-gray-900">Kelola Pengaduan ({{ $pengaduans->total() }})</h1>

    {{-- Filter --}}
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="/admin/pengaduan" style="display:flex;gap:8px;flex-wrap:wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / subjek..."
                   style="padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;width:250px">
            <select name="status" style="padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px">
                <option value="">Semua Status</option>
                <option value="pending"  {{ request('status')=='pending' ?'selected':'' }}>Pending</option>
                <option value="proses"   {{ request('status')=='proses'  ?'selected':'' }}>Proses</option>
                <option value="selesai"  {{ request('status')=='selesai' ?'selected':'' }}>Selesai</option>
            </select>
            <button type="submit" style="padding:6px 16px;background:#2563eb;color:white;border:none;border-radius:6px;cursor:pointer">Filter</button>
            <a href="/admin/pengaduan" style="padding:6px 16px;border:1px solid #d1d5db;border-radius:6px;text-decoration:none;color:#374151;font-size:14px">Reset</a>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($pengaduans->count() > 0)
        <table style="width:100%;border-collapse:collapse;font-size:14px">
            <thead style="background:#f9fafb">
                <tr>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Tanggal</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Nama</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Subjek</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Status</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengaduans as $p)
                <tr style="border-top:1px solid #e5e7eb">
                    <td style="padding:12px 16px;color:#111827">{{ $p->created_at->format('d/m/Y') }}</td>
                    <td style="padding:12px 16px">
                        <div style="font-weight:500;color:#111827">{{ $p->nama_pengadu }}</div>
                        <div style="font-size:12px;color:#6b7280">{{ $p->email }}</div>
                    </td>
                    <td style="padding:12px 16px;color:#111827;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $p->subjek }}</td>
                    <td style="padding:12px 16px">
                        @if($p->status == 'pending')
                            <span style="padding:2px 8px;background:#fef3c7;color:#92400e;border-radius:9999px;font-size:12px">Pending</span>
                        @elseif($p->status == 'proses')
                            <span style="padding:2px 8px;background:#dbeafe;color:#1e40af;border-radius:9999px;font-size:12px">Proses</span>
                        @elseif($p->status == 'selesai')
                            <span style="padding:2px 8px;background:#d1fae5;color:#065f46;border-radius:9999px;font-size:12px">Selesai</span>
                        @else
                            <span style="padding:2px 8px;background:#f3f4f6;color:#374151;border-radius:9999px;font-size:12px">{{ $p->status }}</span>
                        @endif
                    </td>
                    <td style="padding:12px 16px">
                        <a href="/admin/pengaduan/{{ $p->id }}" style="color:#2563eb;margin-right:8px" title="Detail"><i class="fas fa-eye"></i></a>
                        <a href="/admin/pengaduan/{{ $p->id }}/edit" style="color:#16a34a;margin-right:8px" title="Edit"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="/admin/pengaduan/{{ $p->id }}" style="display:inline"
                              onsubmit="return confirm('Hapus pengaduan ini?')">
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
            {{ $pengaduans->withQueryString()->links() }}
        </div>
        @else
        <div style="text-align:center;padding:48px;color:#6b7280">
            <p>Tidak ada pengaduan ditemukan.</p>
            <a href="/admin/pengaduan" style="color:#2563eb">Lihat semua</a>
        </div>
        @endif
    </div>
</div>

@endsection
