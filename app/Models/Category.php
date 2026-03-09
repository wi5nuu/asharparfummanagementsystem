<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        // 'parent_id' // Tambahkan ini jika ingin sistem sub-kategori
    ];

    /**
     * Relasi ke Produk
     * Satu kategori memiliki banyak produk
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Gunakan ini JIKA kamu menambahkan kolom parent_id/category_id di migrasi
    /*
    public function subcategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    */
}