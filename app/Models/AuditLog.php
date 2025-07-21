<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'tags',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the user that performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the auditable model
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Log an action
     */
    public static function log($action, $model = null, $oldValues = null, $newValues = null)
    {
        try {
            $user = Auth::user();
            
            $data = [
                'user_type' => $user ? get_class($user) : null,
                'user_id' => $user ? $user->id : null,
                'event' => $action, // Menggunakan 'event' sesuai dengan migration
                'auditable_type' => $model ? get_class($model) : null,
                'auditable_id' => $model ? $model->id : null,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'url' => Request::fullUrl(),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ];

            self::create($data);
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Log::error('Failed to create audit log: ' . $e->getMessage());
        }
    }

    /**
     * Get action attribute (alias for event)
     */
    public function getActionAttribute()
    {
        return $this->event;
    }

    /**
     * Set action attribute (alias for event)
     */
    public function setActionAttribute($value)
    {
        $this->attributes['event'] = $value;
    }

    /**
     * Get model type attribute (alias for auditable_type)
     */
    public function getModelTypeAttribute()
    {
        return $this->auditable_type;
    }

    /**
     * Get model id attribute (alias for auditable_id)
     */
    public function getModelIdAttribute()
    {
        return $this->auditable_id;
    }

    /**
     * Get action label for display
     */
    public function getActionLabelAttribute()
    {
        return ucfirst($this->event);
    }

    /**
     * Get model name for display
     */
    public function getModelNameAttribute()
    {
        return $this->auditable_type ? class_basename($this->auditable_type) : 'N/A';
    }
}
