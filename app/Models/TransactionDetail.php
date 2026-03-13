<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'size',
        'price',
        'subtotal',
        'purchase_price',
        'bonus_quantity',
        'bonus_note',
    ];

    protected $casts = [
        'quantity'       => 'integer',
        'bonus_quantity' => 'integer',
        'unit_price'     => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'subtotal'       => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if this item has a bonus
     */
    public function hasBonus(): bool
    {
        return $this->bonus_quantity > 0;
    }
}
