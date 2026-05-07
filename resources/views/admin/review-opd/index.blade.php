@extends('layouts.admin')
@section('title', 'Daftar Review OPD')
@section('main-content')

<div class="space-y-4">
    <div style="display:flex;justify-content:space-between;align-items:center">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Review OPD</h1>
        <a href="{{ route('admin.review-opd.create') }}"
           style="padding:8px 16px;background:#2563eb;color:white;text-decoration:none;border-radius:6px;font-size:14px">
            <i class="fas fa-plus mr-1"></i> Tambah
        </a>
    </div>

    @if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:6px;padding:10px 14px;color:#065f46;font-size:14px">
        {{ session('success') }}
    </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.review-opd.index') }}" style="display:flex;gap:8px;flex-wrap:wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama OPD..."
                   style="padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;width:240px">
            <select name="status" style="padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px">
                <option value="">Semua Status</option>
                <option value="dijadwalkan"     {{ request('status')=='dijadwalkan'     ? 'selected':'' }}>Dijadwalkan</option>
                <option value="sedang_berjalan" {{ request('status')=='sedang_berjalan' ? 'selected':'' }}>Sedang Berjalan</option>
                <option value="selesai"         {{ request('status')=='selesai'         ? 'selected':'' }}>Selesai</option>
            </select>
            <button type="submit" style="padding:6px 16px;background:#2563eb;color:white;border:none;border-radius:6px;cursor:pointer">Filter</button>
            <a href="{{ route('admin.review-opd.index') }}" style="padding:6px 16px;border:1px solid #d1d5db;border-radius:6px;text-decoration:none;color:#374151;font-size:14px">Reset</a>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($reviews->count() > 0)
        <table style="width:100%;border-collapse:collapse;font-size:14px">
            <thead style="background:#f9fafb">
                <tr>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase;width:60px">No</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Nama OPD</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Tanggal Review</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Status Review</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Hasil Review</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Keterangan</th>
                    <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $i => $r)
                <tr style="border-top:1px solid #e5e7eb">
                    <td style="padding:12px 16px;color:#6b7280">{{ $reviews->firstItem() + $i }}</td>
                    <td style="padding:12px 16px;color:#111827;font-weight:500">{{ $r->nama_opd }}</td>
                    <td style="padding:12px 16px;color:#374151;white-space:nowrap">{{ $r->tanggal_review->format('d/m/Y') }}</td>
                    <td style="padding:12px 16px">
                        @if($r->status_review == 'dijadwalkan')
                            <span style="padding:2px 8px;background:#fef3c7;color:#92400e;border-radius:9999px;font-size:12px">Dijadwalkan</span>
                        @elseif($r->status_review == 'sedang_berjalan')
                            <span style="padding:2px 8px;background:#dbeafe;color:#1e40af;border-radius:9999px;font-size:12px">Sedang Berjalan</span>
                        @else
                            <span style="padding:2px 8px;background:#d1fae5;color:#065f46;border-radius:9999px;font-size:12px">Selesai</span>
                        @endif
                    </td>
                    <td style="padding:12px 16px;color:#374151">{{ $r->hasil_review ?: '-' }}</td>
                    <td style="padding:12px 16px;color:#6b7280;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $r->keterangan ?: '-' }}</td>
                    <td style="padding:12px 16px;white-space:nowrap">
                        <a href="{{ route('admin.review-opd.edit', $r->id) }}" style="color:#16a34a;margin-right:10px" title="Edit"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.review-opd.destroy', $r->id) }}" style="display:inline"
                              onsubmit="return confirm('Hapus data ini?')">
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
            {{ $reviews->links() }}
        </div>
        @else
        <div style="text-align:center;padding:48px;color:#6b7280">
            <i class="fas fa-clipboard-list" style="font-size:32px;margin-bottom:12px;display:block;color:#d1d5db"></i>
            <p>Belum ada data review OPD.</p>
        </div>
        @endif
    </div>
</div>

@endsection
