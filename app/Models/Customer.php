<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_code',
        'name',
        'phone',
        'email',
        'type',
        'address',
        'is_active',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
