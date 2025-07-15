<?php

namespace App\Actions\Wbs;

use App\Models\Wbs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateWbsReportAction
{
    public function execute(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $wbs = Wbs::find($id);
            
            if (!$wbs) {
                return false;
            }

            // Handle file upload
            if (isset($data['attachment']) && $data['attachment']) {
                // Delete old file if exists
                if ($wbs->attachment) {
                    Storage::disk('public')->delete($wbs->attachment);
                }
                
                $data['attachment'] = $data['attachment']
                    ->store('wbs/attachments', 'public');
            }

            // Set updated by
            $data['updated_by'] = auth()->id();

            // Update WBS report
            return $wbs->update($data);
        });
    }
}
