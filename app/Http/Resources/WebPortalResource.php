<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebPortalResource extends JsonResource
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
            'nama_portal' => $this->nama_portal,
            'deskripsi' => $this->deskripsi,
            'url_portal' => $this->url_portal,
            'kategori' => $this->kategori,
            'icon' => $this->icon,
            'is_active' => $this->is_active,
            'urutan' => $this->urutan,
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
