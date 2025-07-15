<?php

namespace App\Actions\Pelayanan;

use App\Models\Pelayanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdatePelayananAction
{
    public function execute(Pelayanan $pelayanan, array $data): bool
    {
        return DB::transaction(function () use ($pelayanan, $data) {
            // Handle file upload
            if (isset($data['file_formulir']) && $data['file_formulir']) {
                // Delete old file if exists
                if ($pelayanan->file_formulir) {
                    Storage::disk('public')->delete($pelayanan->file_formulir);
                }
                
                $data['file_formulir'] = $data['file_formulir']
                    ->store('pelayanan/formulir', 'public');
            }

            // Set updated by
            $data['updated_by'] = auth()->id();

            // Update pelayanan
            return $pelayanan->update($data);
        });
    }
}
