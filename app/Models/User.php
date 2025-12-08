<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'country',
        'address',
        'latitude',
        'longitude',
        'email_verified_at',
        'role',
        'image',
        'stripe_account_id',
        'stripe_status',
        'stripe_onboarded_at',
        'charges_enabled',
        'payouts_enabled'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'stripe_onboarded_at' => 'datetime',
        'charges_enabled' => 'boolean',
        'payouts_enabled' => 'boolean',
        'password' => 'hashed',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function vendorOrders()
    {
        return $this->hasMany(Order::class, 'vender_id');
    }

    public function customerOrders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class, 'vendor_id');
    }

    /**
     * Check if vendor is fully onboarded with Stripe
     */
    public function isStripeOnboarded(): bool
    {
        return !empty($this->stripe_account_id) 
            && $this->stripe_status === 'complete' 
            && $this->charges_enabled 
            && $this->payouts_enabled;
    }

    /**
     * Check if vendor can accept payments
     */
    public function canAcceptPayments(): bool
    {
        return !empty($this->stripe_account_id) && $this->charges_enabled;
    }
}
