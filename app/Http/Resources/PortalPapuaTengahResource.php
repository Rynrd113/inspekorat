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
            'slug' => $this->slug,
            'konten' => $this->konten,
            'penulis' => $this->penulis,
            'kategori' => $this->kategori,
            'thumbnail' => $this->thumbnail ? asset('storage/' . $this->thumbnail) : null,
            'is_featured' => $this->is_featured,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : null,
            'tags' => $this->tags,
            'meta_description' => $this->meta_description,
            'views' => $this->views,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
