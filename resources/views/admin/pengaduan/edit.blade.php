@extends('layouts.admin')
@section('title', 'Edit Pengaduan')
@section('main-content')

<div class="space-y-4">
    <div style="display:flex;align-items:center;gap:12px">
        <a href="/admin/pengaduan" style="color:#6b7280;text-decoration:none"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Pengaduan</h1>
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

    <div class="bg-white rounded-lg shadow p-6" style="max-width:800px">
        <form method="POST" action="/admin/pengaduan/{{ $pengaduan->id }}">
            @csrf
            @method('PUT')

            <div style="display:grid;gap:16px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Nama Pengadu</label>
                    <input type="text" name="nama_pengadu" value="{{ old('nama_pengadu', $pengaduan->nama_pengadu) }}"
                           style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Email</label>
                    <input type="email" name="email" value="{{ old('email', $pengaduan->email) }}"
                           style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $pengaduan->telepon) }}"
                           style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Kategori</label>
                    <input type="text" name="kategori" value="{{ old('kategori', $pengaduan->kategori) }}"
                           style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Subjek</label>
                    <input type="text" name="subjek" value="{{ old('subjek', $pengaduan->subjek) }}"
                           style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Isi Pengaduan</label>
                    <textarea name="isi_pengaduan" rows="5"
                              style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box;resize:vertical">{{ old('isi_pengaduan', $pengaduan->isi_pengaduan) }}</textarea>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Status</label>
                    <select name="status" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box">
                        <option value="pending"  {{ old('status', $pengaduan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="proses"   {{ old('status', $pengaduan->status) == 'proses'  ? 'selected' : '' }}>Proses</option>
                        <option value="selesai"  {{ old('status', $pengaduan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px">Tanggapan</label>
                    <textarea name="tanggapan" rows="4"
                              style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;box-sizing:border-box;resize:vertical">{{ old('tanggapan', $pengaduan->tanggapan) }}</textarea>
                </div>
            </div>

            <div style="margin-top:20px;display:flex;gap:8px">
                <button type="submit"
                        style="padding:8px 20px;background:#2563eb;color:white;border:none;border-radius:6px;font-size:14px;cursor:pointer">
                    Simpan
                </button>
                <a href="/admin/pengaduan"
                   style="padding:8px 16px;border:1px solid #d1d5db;color:#374151;text-decoration:none;border-radius:6px;font-size:14px">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
