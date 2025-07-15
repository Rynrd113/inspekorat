<?php

namespace App\Traits;

use App\Models\AuditLog;

trait HasAuditLog
{
    public static function bootHasAuditLog()
    {
        static::created(function ($model) {
            AuditLog::log('created', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            AuditLog::log('updated', $model, $model->getOriginal(), $model->getAttributes());
        });

        static::deleted(function ($model) {
            AuditLog::log('deleted', $model, $model->getAttributes(), null);
        });
    }

    /**
     * Log a custom action for this model
     */
    public function logAction($action, $oldValues = null, $newValues = null)
    {
        AuditLog::log($action, $this, $oldValues, $newValues);
    }
}
