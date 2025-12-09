<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'sub_category',
        'name',
        'description',
        'quantity',
        'price',
        'image',
        'discount_percentage',
        'valid_from',
        'valid_until',
        'pickup_start_time',
        'pickup_end_time',
        'user_id',
        'rejection_reason',
        'commission',
        'requested_commission',
        'commission_status',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'image' => 'array', // Cast JSON image column to array
    ];

    protected $appends = ['discounted_price', 'images'];


    public function getDiscountedPriceAttribute()
    {
        $now = Carbon::now()->timestamp;

        $validFrom = (int) $this->valid_from;
        $validUntil = (int) $this->valid_until;

        $hasValidDiscount = $this->discount_percentage &&
            $validFrom &&
            $validUntil &&
            $now >= $validFrom &&
            $now <= $validUntil;

        if ($hasValidDiscount) {
            $discountAmount = ($this->discount_percentage / 100) * $this->price;
            return round($this->price - $discountAmount, 1);
        }

        return $this->price;
    }

    /**
     * Get images as array accessor
     * Handles both old single image string and new JSON array format
     */
    public function getImagesAttribute()
    {
        if (is_null($this->image)) {
            return [];
        }

        // If already an array (from cast), return it
        if (is_array($this->image)) {
            return $this->image;
        }

        // Handle old format (single image string)
        if (is_string($this->image)) {
            // Check if it's JSON
            $decoded = json_decode($this->image, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
            // Old single image format
            return [$this->image];
        }

        return [];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
