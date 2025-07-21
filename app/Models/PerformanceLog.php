<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'method',
        'response_time',
        'memory_usage',
        'status_code',
        'user_id',
        'ip_address',
        'user_agent',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'response_time' => 'float',
        'memory_usage' => 'integer',
        'status_code' => 'integer',
    ];

    /**
     * Get the user that owns the performance log
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
