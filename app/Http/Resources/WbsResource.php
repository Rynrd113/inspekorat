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
        $attachments = [];
        
        // Legacy single file
        if ($this->attachment) {
            $attachments[] = asset('storage/' . $this->attachment);
        }
        
        // Single bukti_file
        if ($this->bukti_file) {
            $attachments[] = asset('storage/' . $this->bukti_file);
        }
        
        // Multiple files
        if ($this->bukti_files && is_array($this->bukti_files)) {
            foreach ($this->bukti_files as $filePath) {
                $attachments[] = asset('storage/' . $filePath);
            }
        }

        return [
            'id' => $this->id,
            'nama_pelapor' => $this->nama_pelapor,
            'email' => $this->email,
            'no_telepon' => $this->no_telepon,
            'subjek' => $this->subjek,
            'deskripsi' => $this->deskripsi,
            'tanggal_kejadian' => $this->tanggal_kejadian ? $this->tanggal_kejadian->format('Y-m-d') : null,
            'lokasi_kejadian' => $this->lokasi_kejadian,
            'pihak_terlibat' => $this->pihak_terlibat,
            'kronologi' => $this->kronologi,
            'attachments' => $attachments,
            'is_anonymous' => $this->is_anonymous,
            'status' => $this->status,
            'response' => $this->response,
            'admin_note' => $this->admin_note,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
