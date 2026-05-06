@extends('layouts.admin')
@section('title', 'Kelola Pengaduan')
@section('main-content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kelola Pengaduan</h1>
        <p class="text-gray-600">Total: {{ $pengaduans->total() }} pengaduan</p>
    </div>

    <x-card>
        <form method="GET" action="{{ route('admin.pengaduan.index') }}" class="flex gap-3 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / subjek..."
                   class="px-3 py-2 border border-gray-300 rounded-md text-sm w-64">
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="proses"  {{ request('status')=='proses' ?'selected':'' }}>Proses</option>
                <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Filter</button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('admin.pengaduan.index') }}" class="px-4 py-2 border border-gray-300 text-sm rounded-md hover:bg-gray-50">Reset</a>
            @endif
        </form>
    </x-card>

    <x-card>
        @if($pengaduans->count())
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase text-xs">Tanggal</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase text-xs">Nama / Email</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase text-xs">Subjek</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase text-xs">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase text-xs">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @foreach($pengaduans as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $p->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">{{ $p->nama_pengadu }}</div>
                        <div class="text-gray-500 text-xs">{{ $p->email }}</div>
                    </td>
                    <td class="px-4 py-3 max-w-xs">
                        <div class="truncate text-gray-900">{{ $p->subjek }}</div>
                        @if($p->kategori)<div class="text-xs text-gray-400">{{ $p->kategori }}</div>@endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @php
                            $cls = ['pending'=>'bg-yellow-100 text-yellow-800','proses'=>'bg-blue-100 text-blue-800','selesai'=>'bg-green-100 text-green-800'][$p->status] ?? 'bg-gray-100 text-gray-800';
                            $lbl = ['pending'=>'Pending','proses'=>'Proses','selesai'=>'Selesai'][$p->status] ?? $p->status;
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $cls }}">{{ $lbl }}</span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ route('admin.pengaduan.show', $p) }}" class="text-blue-600 hover:text-blue-900" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.pengaduan.edit', $p) }}" class="text-green-600 hover:text-green-900" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.pengaduan.destroy', $p) }}" class="inline"
                              onsubmit="return confirm('Hapus pengaduan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $pengaduans->withQueryString()->links() }}</div>
        @else
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-inbox text-4xl mb-3 block"></i>
            Tidak ada pengaduan ditemukan.
        </div>
        @endif
    </x-card>
</div>
@endsection
