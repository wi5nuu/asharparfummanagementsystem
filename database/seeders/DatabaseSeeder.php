<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create product categories if not exists
        $categories = [
            ['name' => 'Premium', 'color' => '#FF6B6B', 'description' => 'Produk premium berkualitas tinggi'],
            ['name' => 'Regular', 'color' => '#4ECDC4', 'description' => 'Produk regular standar'],
            ['name' => 'Refill', 'color' => '#95E1D3', 'description' => 'Produk refill'],
            ['name' => 'Bundle', 'color' => '#FFD93D', 'description' => 'Paket bundle hemat'],
        ];

        foreach ($categories as $category) {
            if (!\App\Models\ProductCategory::where('name', $category['name'])->exists()) {
                \App\Models\ProductCategory::create($category);
            }
        }

        // Create admin user if not exists
        if (!User::where('email', 'admin@apms.local')->exists()) {
            User::create([
                'name' => 'Admin APMS',
                'email' => 'admin@apms.local',
                'phone' => '08123456789',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        // Create cashier user if not exists
        if (!User::where('email', 'cashier@apms.local')->exists()) {
            User::create([
                'name' => 'Cashier User',
                'email' => 'cashier@apms.local',
                'phone' => '08198765432',
                'password' => bcrypt('password'),
                'role' => 'cashier',
            ]);
        }

        // Create sample customers if not exist
        if (!\App\Models\Customer::where('customer_code', 'CUST001')->exists()) {
            \App\Models\Customer::create([
                'customer_code' => 'CUST001',
                'name' => 'PT Mitra Sejaya',
                'phone' => '02156789012',
                'email' => 'contact@mitrajaya.com',
                'type' => 'wholesale',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
                'is_active' => true,
            ]);
        }

        if (!\App\Models\Customer::where('customer_code', 'CUST002')->exists()) {
            \App\Models\Customer::create([
                'customer_code' => 'CUST002',
                'name' => 'Toko Roti Abadi',
                'phone' => '08567890123',
                'email' => 'toko.abadi@email.com',
                'type' => 'retail',
                'address' => 'Jl. Sudirman No. 45, Bandung',
                'is_active' => true,
            ]);
        }
    }
}
