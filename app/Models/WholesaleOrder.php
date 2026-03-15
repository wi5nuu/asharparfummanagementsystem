<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class WholesaleOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_id',
        'package_target_amount',
        'total_amount',
        'status',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'shipping_courier',
        'delivery_handler',
        'packing_days',
        'estimated_arrival',
        'barcode',
        'confirmed_at',
        'completed_at',
    ];

    protected $casts = [
        'estimated_arrival' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(WholesaleOrderDetail::class);
    }
}
