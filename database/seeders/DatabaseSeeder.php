<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin (Pemilik Toko)
        User::updateOrCreate(
            ['email' => 'admin@sikadir.com'],
            [
                'name' => 'Pemilik Toko',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Buat User Kasir
        User::updateOrCreate(
            ['email' => 'kasir@sikadir.com'],
            [
                'name' => 'Budi Kasir',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
            ]
        );

        // 3. Buat Kategori Contoh
        $makanan = Category::create(['name' => 'Makanan']);
        $minuman = Category::create(['name' => 'Minuman']);
        $snack   = Category::create(['name' => 'Snack']);

        // 4. Buat Produk Contoh
        Product::create([
            'barcode' => '899123456701',
            'name' => 'Nasi Goreng Spesial',
            'category_id' => $makanan->id,
            'cost_price' => 15000,
            'selling_price' => 25000,
            'stock' => 30,
            'min_stock' => 5
        ]);

        Product::create([
            'barcode' => '899123456702',
            'name' => 'Es Teh Manis',
            'category_id' => $minuman->id,
            'cost_price' => 2000,
            'selling_price' => 5000,
            'stock' => 50,
            'min_stock' => 10
        ]);

        Product::create([
            'barcode' => '899123456703',
            'name' => 'Keripik Singkong',
            'category_id' => $snack->id,
            'cost_price' => 6000,
            'selling_price' => 10000,
            'stock' => 3,
            'min_stock' => 5
        ]);
    }
}