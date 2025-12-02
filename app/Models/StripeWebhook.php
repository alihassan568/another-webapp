<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeWebhook extends Model
{
    protected $table = 'stripe_webhooks';

    protected $fillable = [
        'stripe_id',
        'event_type',
        'payload',
        'processed'
    ];

    protected $casts = [
        'payload' => 'array',
        'processed' => 'boolean',
    ];

    /**
     * Scope a query to only include unprocessed webhooks.
     */
    public function scopeUnprocessed($query)
    {
        return $query->where('processed', false);
    }

    /**
     * Mark the webhook as processed.
     */
    public function markAsProcessed()
    {
        $this->update(['processed' => true]);
    }

    /**
     * Get the event data from the payload.
     */
    public function getEventData($key = null)
    {
        $data = $this->payload['data']['object'] ?? [];
        
        if ($key) {
            return $data[$key] ?? null;
        }
        
        return $data;
    }
}
