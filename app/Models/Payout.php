<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    protected $fillable = [
        'vendor_id',
        'amount',
        'currency',
        'status',
        'method',
        'reference',
        'notes',
        'stripe_payout_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the vendor that owns the payout.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Scope a query to only include pending payouts.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed payouts.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Mark the payout as failed.
     */
    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'notes' => $reason ?: $this->notes,
        ]);
    }

    /**
     * Mark the payout as completed.
     */
    public function markAsCompleted($reference = null)
    {
        $this->update([
            'status' => 'completed',
            'reference' => $reference ?: $this->reference,
        ]);
    }
}
