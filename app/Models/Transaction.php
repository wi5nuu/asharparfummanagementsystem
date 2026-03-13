<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'customer_type',
        'user_id',
        'subtotal',
        'discount',
        'discount_type',
        'discount_percent',
        'tax_amount',
        'total_amount',
        'final_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'receipt_visibility',
        'payment_status',
        'debt_amount',
        'coupon_id',
        'payment_proof_image',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'debt_amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
