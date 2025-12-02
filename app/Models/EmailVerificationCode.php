<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerificationCode extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'expired_at', 'user_id'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];
}
