<?php

namespace App\Actions\PortalPapuaTengah;

use App\Models\PortalPapuaTengah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdatePortalPapuaTengahAction
{
    public function execute(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $portalPapuaTengah = PortalPapuaTengah::find($id);
            
            if (!$portalPapuaTengah) {
                return false;
            }

            // Update slug if title changed
            if (isset($data['judul']) && $data['judul'] !== $portalPapuaTengah->judul) {
                $data['slug'] = Str::slug($data['judul']);
            }

            // Handle image upload
            if (isset($data['gambar']) && $data['gambar']) {
                // Delete old image if exists
                if ($portalPapuaTengah->gambar) {
                    Storage::disk('public')->delete($portalPapuaTengah->gambar);
                }
                
                $data['gambar'] = $data['gambar']
                    ->store('portal-papua-tengah/images', 'public');
            }

            // Update Portal Papua Tengah
            return $portalPapuaTengah->update($data);
        });
    }
}
