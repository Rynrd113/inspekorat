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
            'status' => $this->status,
            'status_badge' => $this->status_badge,
            'tanggapan' => $this->tanggapan,
            'attachment' => $this->attachment,
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i'),
            'created_at_human' => $this->created_at?->diffForHumans(),
        ];
    }
}
