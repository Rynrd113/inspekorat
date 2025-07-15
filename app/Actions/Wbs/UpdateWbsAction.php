<?php

namespace App\Actions\Wbs;

use App\Models\Wbs;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateWbsAction
{
    public function execute(Wbs $wbs, array $data): bool
    {
        return DB::transaction(function () use ($wbs, $data) {
            $oldData = $wbs->toArray();
            
            // Handle file uploads
            if (isset($data['bukti_files']) && $data['bukti_files']) {
                // Delete old files if exists
                if ($wbs->bukti_files) {
                    foreach ($wbs->bukti_files as $file) {
                        Storage::disk('public')->delete($file);
                    }
                }
                
                $uploadedFiles = [];
                foreach ($data['bukti_files'] as $file) {
                    $uploadedFiles[] = $file->store('wbs/bukti', 'public');
                }
                $data['bukti_files'] = $uploadedFiles;
            }

            // Handle single file upload (legacy support)
            if (isset($data['bukti_file']) && $data['bukti_file']) {
                // Delete old file if exists
                if ($wbs->bukti_file) {
                    Storage::disk('public')->delete($wbs->bukti_file);
                }
                
                $data['bukti_file'] = $data['bukti_file']->store('wbs/bukti', 'public');
            }

            // Update WBS report
            $result = $wbs->update($data);

            // Log activity
            if ($result) {
                AuditLog::log('updated', $wbs->fresh(), $oldData, $data);
            }

            return $result;
        });
    }
}
