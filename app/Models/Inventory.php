<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    // Tentukan kolom mana saja yang boleh diisi secara massal
    protected $fillable = [
        'product_id',
        'current_stock',
        'minimum_stock',
        'cost_per_unit',
        'expiration_date',
        'batch_number',
        'location'
    ];

    // Casting tipe data agar Laravel otomatis mengubahnya menjadi objek Carbon (tanggal)
    protected $casts = [
        'expiration_date' => 'date',
        'current_stock' => 'integer',
        'minimum_stock' => 'integer',
        'cost_per_unit' => 'decimal:2'
    ];

    /**
     * Relasi ke model Product.
     * Satu data inventory dimiliki oleh satu produk.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}