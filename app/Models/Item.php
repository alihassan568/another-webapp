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

    protected $appends = ['discounted_price'];


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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
