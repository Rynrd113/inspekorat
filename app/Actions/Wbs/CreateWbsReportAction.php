<?php

namespace App\Actions\Wbs;

use App\Models\Wbs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateWbsReportAction
{
    public function execute(array $data): Wbs
    {
        return DB::transaction(function () use ($data) {
            // Generate unique report ID
            $data['report_id'] = 'WBS-' . strtoupper(Str::random(8));
            
            // Set default values
            $data['status'] = 'pending';
            $data['submitted_at'] = now();
            $data['is_anonymous'] = $data['is_anonymous'] ?? false;

            // Handle file upload
            if (isset($data['attachment']) && $data['attachment']) {
                $data['attachment'] = $data['attachment']
                    ->store('wbs/attachments', 'public');
            }

            // Create WBS report
            $wbs = Wbs::create($data);

            return $wbs;
        });
    }
}
