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
        'aroma_preferences',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->customer_code)) {
                $latestCustomer = static::latest('id')->first();
                $nextId = $latestCustomer ? $latestCustomer->id + 1 : 1;
                $customer->customer_code = 'CUST-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
