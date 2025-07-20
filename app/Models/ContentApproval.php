<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'approvable_type',
        'approvable_id',
        'submitted_by',
        'approved_by',
        'status',
        'notes',
        'submitted_at',
        'reviewed_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_REVISION = 'revision';

    /**
     * Get the model that needs approval
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Get the user who submitted
     */
    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the user who approved
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who rejected
     */
    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Scope for pending approvals
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for approved content
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for rejected content
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Submit for approval
     */
    public function submitForApproval($notes = null)
    {
        $this->update([
            'status' => self::STATUS_PENDING,
            'submission_notes' => $notes,
            'submitted_at' => now(),
            'submitted_by' => auth()->id()
        ]);

        AuditLog::log('submitted_for_approval', $this->model, null, [
            'notes' => $notes,
            'submitted_by' => auth()->user()->name
        ]);
    }

    /**
     * Approve content
     */
    public function approve($notes = null)
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approval_notes' => $notes,
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);

        AuditLog::log('approved', $this->model, null, [
            'notes' => $notes,
            'approved_by' => auth()->user()->name
        ]);
    }

    /**
     * Reject content
     */
    public function reject($notes = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_notes' => $notes,
            'rejected_at' => now(),
            'rejected_by' => auth()->id()
        ]);

        AuditLog::log('rejected', $this->model, null, [
            'notes' => $notes,
            'rejected_by' => auth()->user()->name
        ]);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu Persetujuan',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_REVISION => 'Perlu Revisi',
            default => $this->status
        };
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_APPROVED => 'green',
            self::STATUS_REJECTED => 'red',
            self::STATUS_REVISION => 'orange',
            default => 'gray'
        };
    }

    /**
     * Check if pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if approved
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if rejected
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
