<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_by',
        'order_status',
        'payment_status',
        'total_price',
        'commission_amount',
        'vendor_amount',
        'currency',
        'user_id',
        'vender_id',
        'stripe_payment_intent_id',
        'stripe_transfer_id'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'vendor_amount' => 'decimal:2',
    ];

    /**
     * Get the vendor for this order
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vender_id');
    }

    /**
     * Get the customer for this order
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the order items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Calculate commission for this order
     */
    public function calculateCommission()
    {
        $commissionSettings = CommissionSetting::getSettings();
        $totalPrice = $this->total_price;
        
        // Calculate commission based on percentage
        $commissionRate = $commissionSettings->default_commission;
        $commissionAmount = ($totalPrice * $commissionRate) / 100;
        
        $this->commission_amount = $commissionAmount;
        $this->vendor_amount = $totalPrice - $commissionAmount;
        $this->save();
        
        return $commissionAmount;
    }

    /**
     * Get total price in cents for Stripe
     */
    public function getTotalPriceInCents(): int
    {
        return (int) ($this->total_price * 100);
    }

    /**
     * Get commission amount in cents for Stripe
     */
    public function getCommissionInCents(): int
    {
        return (int) ($this->commission_amount * 100);
    }

    /**
     * Mark order as paid
     */
    public function markAsPaid($paymentIntentId, $transferId = null)
    {
        $this->payment_status = 'paid';
        $this->stripe_payment_intent_id = $paymentIntentId;
        if ($transferId) {
            $this->stripe_transfer_id = $transferId;
        }
        $this->save();
    }

    /**
     * Mark order as payment failed
     */
    public function markAsPaymentFailed()
    {
        $this->payment_status = 'failed';
        $this->save();
    }
}
