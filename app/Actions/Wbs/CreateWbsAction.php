<?php

namespace App\Actions\Wbs;

use App\Models\Wbs;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class CreateWbsAction
{
    public function execute(array $data): Wbs
    {
        return DB::transaction(function () use ($data) {
            // Handle file uploads
            if (isset($data['bukti_files']) && $data['bukti_files']) {
                $uploadedFiles = [];
                foreach ($data['bukti_files'] as $file) {
                    $uploadedFiles[] = $file->store('wbs/bukti', 'public');
                }
                $data['bukti_files'] = $uploadedFiles;
            }

            // Handle single file upload (legacy support)
            if (isset($data['bukti_file']) && $data['bukti_file']) {
                $data['bukti_file'] = $data['bukti_file']->store('wbs/bukti', 'public');
            }

            // Set default values
            $data['status'] = 'pending';
            $data['is_anonymous'] = $data['is_anonymous'] ?? false;

            // Create WBS report
            $wbs = Wbs::create($data);

            // Log activity
            AuditLog::log('created', $wbs, null, $wbs->toArray());

            return $wbs;
        });
    }
}
