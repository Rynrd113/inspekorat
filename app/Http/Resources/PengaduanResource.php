<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengaduanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_pengadu' => $this->nama_pengadu,
            'email' => $this->email,
            'telepon' => $this->telepon,
            'subjek' => $this->subjek,
            'isi_pengaduan' => $this->isi_pengaduan,
            'kategori' => $this->kategori,
            'status' => $this->status,
            'tanggapan' => $this->tanggapan,
            'attachment' => $this->attachment,
            'bukti_files' => $this->bukti_files,
            'is_anonymous' => $this->is_anonymous,
            'created_at' => $this->created_at ? $this->created_at->toISOString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toISOString() : null,
            'tanggal_pengaduan' => $this->tanggal_pengaduan ? $this->tanggal_pengaduan->format('Y-m-d H:i:s') : null,
            'created_at_human' => $this->created_at ? $this->created_at->diffForHumans() : null,
        ];
    }
}
