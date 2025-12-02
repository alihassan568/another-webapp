<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    use HasFactory;

    protected $fillable = [
       'business_type',
       'owner_name',
       'opening_time',
       'close_time',
       'bank_title',
       'bank_name',
       'iban',
       'user_id'
    ];
}
