<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionSetting extends Model
{
    protected $fillable = [
        'type',    // e.g. default, category, vendor
        'rate',    // percentage
        'active',
        'stripe_fee_percentage',
        'stripe_fee_fixed',
        'tax_percentage',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'active' => 'boolean',
        'stripe_fee_percentage' => 'decimal:2',
        'stripe_fee_fixed' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
    ];

    /**
     * Get the tax percentage (default 0.00)
     */
    public static function getTaxPercentage(): float
    {
        return (float) (self::getSettings()->tax_percentage ?? 0.00);
    }

    public static function getSettings()
    {
        $settings = self::where('type', 'default')->first();

        if (!$settings) {
            // Fallback if table structure matches migration or model
            // We try to find ANY record or create one
            $settings = self::first();
            if (!$settings) {
                 $settings = self::create([
                    'type' => 'default',
                    'rate' => 10.00,
                    'active' => true,
                    'stripe_fee_percentage' => 2.90,
                    'stripe_fee_fixed' => 0.30,
                ]);
            }
        }

        return $settings;
    }

    public static function getStripeFees(): array
    {
        $settings = self::getSettings();
        return [
            'percentage' => (float) ($settings->stripe_fee_percentage ?? 2.90),
            'fixed' => (float) ($settings->stripe_fee_fixed ?? 0.30),
        ];
    }

    /**
     * Get the default commission rate (percentage)
     */
    public static function getDefaultRate(): float
    {
        return (float) self::getSettings()->rate;
    }

    /**
     * Calculate commission for a given amount using the current rate.
     * Kept for backwards compatibility with existing callers.
     */
    public function calculateCommission($vendorId, $amount)
    {
        $commissionRate = (float) $this->rate;
        $commission = ($amount * $commissionRate) / 100;

        return [
            'rate' => $commissionRate,
            'amount' => $commission,
            'net_amount' => $amount - $commission,
        ];
    }
}
