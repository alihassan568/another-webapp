<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_user_id',
        'invited_by_user_id',
        'action',
        'action_type',
        'description',
        'target_id',
        'target_type',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the admin user who performed the action
     */
    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    /**
     * Get the user who invited this admin
     */
    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by_user_id');
    }

    /**
     * Get the target model (polymorphic-like behavior)
     */
    public function getTargetAttribute()
    {
        if (!$this->target_id || !$this->target_type) {
            return null;
        }

        switch ($this->target_type) {
            case 'item':
                return Item::find($this->target_id);
            case 'role':
                return Role::find($this->target_id);
            case 'invite':
                return Invite::find($this->target_id);
            case 'user':
                return User::find($this->target_id);
            default:
                return null;
        }
    }

    /**
     * Scope to filter by admin user
     */
    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_user_id', $adminId);
    }

    /**
     * Scope to filter by invited by user
     */
    public function scopeByInviter($query, $inviterId)
    {
        return $query->where('invited_by_user_id', $inviterId);
    }

    /**
     * Scope to filter by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }
}
