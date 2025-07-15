<?php

namespace App\Actions\Pelayanan;

use App\Models\Pelayanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreatePelayananAction
{
    public function execute(array $data): Pelayanan
    {
        return DB::transaction(function () use ($data) {
            // Handle file upload
            if (isset($data['file_formulir']) && $data['file_formulir']) {
                $data['file_formulir'] = $data['file_formulir']
                    ->store('pelayanan/formulir', 'public');
            }

            // Set default values
            $data['created_by'] = auth()->id();
            $data['status'] = $data['status'] ?? false;

            // Create pelayanan
            $pelayanan = Pelayanan::create($data);

            // Assign default urutan if not provided
            if (!isset($data['urutan'])) {
                $pelayanan->update(['urutan' => $pelayanan->id]);
            }

            return $pelayanan;
        });
    }
}
