<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOTP extends Model
{
    use HasFactory;

    protected $fillable = ['otp', 'token', 'user_id', 'expired_at'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];
}
