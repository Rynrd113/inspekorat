<?php

namespace App\Actions\PortalPapuaTengah;

use App\Models\PortalPapuaTengah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreatePortalPapuaTengahAction
{
    public function execute(array $data): PortalPapuaTengah
    {
        return DB::transaction(function () use ($data) {
            // Generate slug if not provided
            if (!isset($data['slug']) || empty($data['slug'])) {
                $data['slug'] = Str::slug($data['judul']);
            }

            // Handle image upload
            if (isset($data['gambar']) && $data['gambar']) {
                $data['gambar'] = $data['gambar']
                    ->store('portal-papua-tengah/images', 'public');
            }

            // Set default values
            $data['published_at'] = $data['published_at'] ?? now();
            $data['is_published'] = $data['is_published'] ?? true;
            $data['views'] = 0;

            // Create Portal Papua Tengah
            $portalPapuaTengah = PortalPapuaTengah::create($data);

            return $portalPapuaTengah;
        });
    }
}
