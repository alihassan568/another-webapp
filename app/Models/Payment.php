<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'vendor_id',
        'user_id',
        'amount',
        'currency',
        'application_fee_amount',
        'stripe_payment_intent_id',
        'stripe_charge_id',
        'stripe_transfer_id',
        'status',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'application_fee_amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function markAsSucceeded(): void
    {
        $this->status = 'succeeded';
        $this->save();
    }

    public function markAsFailed(): void
    {
        $this->status = 'failed';
        $this->save();
    }
}
