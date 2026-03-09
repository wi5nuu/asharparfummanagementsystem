<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

protected $fillable = [
    'name',
    'barcode',
    'product_category_id', // Gunakan nama ini, bukan category_id
    'brand',
    'size',
    'unit',
    'purchase_price',
    'selling_price',
    'wholesale_price',
    'initial_stock',
    'image',
    'description'
];

public function category()
{
    // Hubungkan ke Model ProductCategory
    return $this->belongsTo(ProductCategory::class, 'product_category_id');
}

public function inventory()
{
    return $this->hasOne(Inventory::class);
}

public function inventories()
{
    return $this->hasMany(Inventory::class);
}
}