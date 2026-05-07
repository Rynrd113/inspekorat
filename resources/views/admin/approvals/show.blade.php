@extends('layouts.admin')

@section('title', 'Detail Persetujuan')
@section('header', 'Detail Persetujuan')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.approvals.index') }}" class="text-blue-600 hover:text-blue-800">Persetujuan</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Detail</li>
@endsection

@section('main-content')
<div class="space-y-6" style="max-width:800px">

    @if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:6px;padding:10px 14px;color:#065f46;font-size:14px">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:6px;padding:10px 14px;color:#991b1b;font-size:14px">
        {{ session('error') }}
    </div>
    @endif

    {{-- Info Konten --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 style="font-size:16px;font-weight:600;color:#111827;margin-bottom:16px">Informasi Konten</h3>
        <table style="width:100%;font-size:14px;border-collapse:collapse">
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280;width:160px">Tipe</td>
                <td style="padding:10px 0;color:#111827">
                    <span style="padding:2px 10px;background:#dbeafe;color:#1e40af;border-radius:9999px;font-size:12px">
                        {{ $approval->approvable_type ? class_basename($approval->approvable_type) : 'Unknown' }}
                    </span>
                </td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Judul</td>
                <td style="padding:10px 0;color:#111827;font-weight:500">
                    {{ $approval->approvable ? ($approval->approvable->judul ?? $approval->approvable->title ?? $approval->approvable->nama ?? 'Tanpa judul') : 'Konten tidak ditemukan' }}
                </td>
            </tr>
            @if($approval->approvable && isset($approval->approvable->konten))
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280;vertical-align:top">Isi</td>
                <td style="padding:10px 0;color:#374151;line-height:1.6;font-size:13px">
                    {{ Str::limit(strip_tags($approval->approvable->konten), 300) }}
                </td>
            </tr>
            @endif
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Dikirim oleh</td>
                <td style="padding:10px 0;color:#111827">{{ $approval->submitter->name ?? 'N/A' }}</td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Tanggal Kirim</td>
                <td style="padding:10px 0;color:#111827">{{ $approval->submitted_at ? $approval->submitted_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Status</td>
                <td style="padding:10px 0">
                    @if($approval->status == 'pending')
                        <span style="padding:2px 10px;background:#fef3c7;color:#92400e;border-radius:9999px;font-size:12px">Menunggu</span>
                    @elseif($approval->status == 'approved')
                        <span style="padding:2px 10px;background:#d1fae5;color:#065f46;border-radius:9999px;font-size:12px">Disetujui</span>
                    @else
                        <span style="padding:2px 10px;background:#fee2e2;color:#991b1b;border-radius:9999px;font-size:12px">Ditolak</span>
                    @endif
                </td>
            </tr>
            @if($approval->reviewed_at)
            <tr style="border-bottom:1px solid #e5e7eb">
                <td style="padding:10px 0;color:#6b7280">Diproses</td>
                <td style="padding:10px 0;color:#111827">
                    {{ $approval->reviewed_at->format('d/m/Y H:i') }}
                    @if($approval->approver) — oleh {{ $approval->approver->name }} @endif
                </td>
            </tr>
            @endif
            @if($approval->notes)
            <tr>
                <td style="padding:10px 0;color:#6b7280;vertical-align:top">Catatan</td>
                <td style="padding:10px 0;color:#374151">{{ $approval->notes }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- Aksi (hanya jika pending dan punya hak) --}}
    @if($approval->status == 'pending' && auth()->user()->canApproveContent())
    <div class="bg-white rounded-lg shadow p-6">
        <h3 style="font-size:16px;font-weight:600;color:#111827;margin-bottom:16px">Tindakan</h3>
        <div style="display:flex;gap:12px;flex-wrap:wrap">

            {{-- Approve --}}
            <form method="POST" action="{{ route('admin.approvals.approve', $approval) }}"
                  onsubmit="return confirm('Setujui konten ini? Konten akan langsung tayang.')">
                @csrf
                <input type="hidden" name="notes" value="">
                <button type="submit"
                        style="padding:8px 20px;background:#16a34a;color:white;border:none;border-radius:6px;font-size:14px;cursor:pointer">
                    <i class="fas fa-check mr-1"></i> Setujui & Publikasikan
                </button>
            </form>

            {{-- Reject --}}
            <button type="button" onclick="document.getElementById('rejectForm').style.display='block';this.style.display='none'"
                    style="padding:8px 20px;background:#dc2626;color:white;border:none;border-radius:6px;font-size:14px;cursor:pointer">
                <i class="fas fa-times mr-1"></i> Tolak
            </button>
        </div>

        {{-- Form tolak --}}
        <div id="rejectForm" style="display:none;margin-top:16px">
            <form method="POST" action="{{ route('admin.approvals.reject', $approval) }}">
                @csrf
                <div style="margin-bottom:10px">
                    <label style="font-size:13px;font-weight:500;color:#374151;display:block;margin-bottom:4px">Alasan penolakan <span style="color:#dc2626">*</span></label>
                    <textarea name="notes" rows="3" required
                              style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box"
                              placeholder="Tulis alasan penolakan..."></textarea>
                </div>
                <button type="submit"
                        style="padding:8px 20px;background:#dc2626;color:white;border:none;border-radius:6px;font-size:14px;cursor:pointer">
                    Kirim Penolakan
                </button>
            </form>
        </div>
    </div>
    @endif

    <a href="{{ route('admin.approvals.index') }}"
       style="display:inline-block;padding:8px 16px;border:1px solid #d1d5db;color:#374151;text-decoration:none;border-radius:6px;font-size:14px">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>

</div>
@endsection
