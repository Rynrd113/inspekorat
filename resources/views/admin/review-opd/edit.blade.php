@extends('layouts.admin')
@section('title', 'Edit Review OPD')
@section('main-content')

<div class="space-y-4">
    <div style="display:flex;align-items:center;gap:12px">
        <a href="{{ route('admin.review-opd.index') }}" style="color:#6b7280;text-decoration:none"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Review OPD</h1>
    </div>

    @if($errors->any())
    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:6px;padding:12px;color:#991b1b;font-size:14px">
        <ul style="margin:0;padding-left:16px">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6" style="max-width:700px">
        <form method="POST" action="{{ route('admin.review-opd.update', $reviewOpd->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div style="display:grid;gap:16px">

                <div style="display:grid;grid-template-columns:1fr 160px;gap:12px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Nama OPD <span style="color:#dc2626">*</span></label>
                        <input type="text" name="nama_opd" value="{{ old('nama_opd', $reviewOpd->nama_opd) }}" required
                               style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Tahun Anggaran <span style="color:#dc2626">*</span></label>
                        <select name="tahun_anggaran" required
                                style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                            @for($y = date('Y') + 1; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ old('tahun_anggaran', $reviewOpd->tahun_anggaran)==$y ? 'selected':'' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Tanggal Mulai Review <span style="color:#dc2626">*</span></label>
                        <input type="date" name="tanggal_review" value="{{ old('tanggal_review', $reviewOpd->tanggal_review->format('Y-m-d')) }}" required
                               style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai"
                               value="{{ old('tanggal_selesai', $reviewOpd->tanggal_selesai?->format('Y-m-d')) }}"
                               style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Status Review <span style="color:#dc2626">*</span></label>
                    <select name="status_review" required
                            style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                        <option value="dijadwalkan"     {{ old('status_review', $reviewOpd->status_review)=='dijadwalkan'     ? 'selected':'' }}>Dijadwalkan</option>
                        <option value="sedang_berjalan" {{ old('status_review', $reviewOpd->status_review)=='sedang_berjalan' ? 'selected':'' }}>Sedang Berjalan</option>
                        <option value="selesai"         {{ old('status_review', $reviewOpd->status_review)=='selesai'         ? 'selected':'' }}>Selesai</option>
                    </select>
                </div>

                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Hasil Review</label>
                    <input type="text" name="hasil_review" value="{{ old('hasil_review', $reviewOpd->hasil_review) }}"
                           placeholder="Contoh: Baik, Cukup, Perlu Perbaikan..."
                           style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                </div>

                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Keterangan</label>
                    <textarea name="keterangan" rows="4"
                              style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box;resize:vertical">{{ old('keterangan', $reviewOpd->keterangan) }}</textarea>
                </div>

                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">
                        Dokumen Hasil Review <span style="color:#6b7280;font-weight:400">(PDF, maks. 10 MB)</span>
                    </label>

                    @if($reviewOpd->dokumen_path)
                    <div style="margin-bottom:8px;padding:10px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:6px;display:flex;align-items:center;justify-content:space-between">
                        <span style="font-size:13px;color:#166534">
                            <i class="fas fa-file-pdf mr-2 text-red-500"></i>
                            Dokumen sudah ada —
                            <a href="{{ Storage::url($reviewOpd->dokumen_path) }}" target="_blank" style="color:#2563eb">Lihat dokumen</a>
                        </span>
                        <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:#374151;cursor:pointer">
                            <input type="checkbox" name="hapus_dokumen" value="1"> Hapus dokumen
                        </label>
                    </div>
                    <p style="font-size:12px;color:#6b7280;margin-bottom:6px">Upload file baru untuk mengganti dokumen yang ada.</p>
                    @endif

                    <input type="file" name="dokumen" accept=".pdf"
                           style="width:100%;padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                </div>

            </div>
            <div style="margin-top:20px;display:flex;gap:8px">
                <button type="submit"
                        style="padding:8px 20px;background:#2563eb;color:white;border:none;border-radius:6px;font-size:14px;cursor:pointer">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.review-opd.index') }}"
                   style="padding:8px 16px;border:1px solid #d1d5db;color:#374151;text-decoration:none;border-radius:6px;font-size:14px">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
