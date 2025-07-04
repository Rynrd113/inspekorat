<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WbsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_pelapor' => $this->nama_pelapor,
            'email' => $this->email,
            'no_telepon' => $this->no_telepon,
            'subjek' => $this->subjek,
            'deskripsi' => $this->deskripsi,
            'attachment' => $this->attachment ? asset('storage/' . $this->attachment) : null,
            'is_anonymous' => $this->is_anonymous,
            'status' => $this->status,
            'tanggapan' => $this->tanggapan,
            'admin_note' => $this->admin_note,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
