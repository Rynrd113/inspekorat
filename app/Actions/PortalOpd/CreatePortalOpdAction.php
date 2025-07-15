<?php

namespace App\Actions\PortalOpd;

use App\Models\PortalOpd;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class CreatePortalOpdAction
{
    public function execute(array $data): PortalOpd
    {
        return DB::transaction(function () use ($data) {
            // Handle file uploads
            if (isset($data['logo']) && $data['logo']) {
                $data['logo'] = $data['logo']->store('portal-opd/logo', 'public');
            }

            if (isset($data['banner']) && $data['banner']) {
                $data['banner'] = $data['banner']->store('portal-opd/banner', 'public');
            }

            // Set default values
            $data['created_by'] = auth()->id();
            $data['status'] = $data['status'] ?? true;

            // Create Portal OPD
            $portalOpd = PortalOpd::create($data);

            // Log activity
            AuditLog::log('created', $portalOpd, null, $portalOpd->toArray());

            return $portalOpd;
        });
    }
}
