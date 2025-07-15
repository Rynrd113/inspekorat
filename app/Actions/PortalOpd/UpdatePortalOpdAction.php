<?php

namespace App\Actions\PortalOpd;

use App\Models\PortalOpd;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdatePortalOpdAction
{
    public function execute(PortalOpd $portalOpd, array $data): bool
    {
        return DB::transaction(function () use ($portalOpd, $data) {
            $oldData = $portalOpd->toArray();
            
            // Handle file uploads
            if (isset($data['logo']) && $data['logo']) {
                // Delete old logo if exists
                if ($portalOpd->logo) {
                    Storage::disk('public')->delete($portalOpd->logo);
                }
                
                $data['logo'] = $data['logo']->store('portal-opd/logo', 'public');
            }

            if (isset($data['banner']) && $data['banner']) {
                // Delete old banner if exists
                if ($portalOpd->banner) {
                    Storage::disk('public')->delete($portalOpd->banner);
                }
                
                $data['banner'] = $data['banner']->store('portal-opd/banner', 'public');
            }

            // Set updated by
            $data['updated_by'] = auth()->id();

            // Update Portal OPD
            $result = $portalOpd->update($data);

            // Log activity
            if ($result) {
                AuditLog::log('updated', $portalOpd->fresh(), $oldData, $data);
            }

            return $result;
        });
    }
}
