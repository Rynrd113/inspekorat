<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortalPapuaTengahResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'konten' => $this->konten,
            'author' => $this->author,
            'kategori' => $this->kategori,
            'gambar' => $this->gambar ? asset('storage/' . $this->gambar) : null,
            'status' => $this->status,
            'tanggal_publikasi' => $this->tanggal_publikasi ? $this->tanggal_publikasi->format('Y-m-d') : null,
            'views' => $this->views ?? 0,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
