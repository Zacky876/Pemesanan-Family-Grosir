<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'category_id' => 1,
                'code' => 'PRD001',
                'name' => 'Indomie Goreng',
                'price' => 3500,
                'stock' => 100,
                'unit' => 'pcs',
                'description' => 'Mi instan goreng',
            ],
            [
                'category_id' => 1,
                'code' => 'PRD002',
                'name' => 'Biskuit',
                'price' => 8000,
                'stock' => 50,
                'unit' => 'pcs',
                'description' => 'Biskuit kemasan',
            ],
            [
                'category_id' => 2,
                'code' => 'PRD003',
                'name' => 'Aqua 600ml',
                'price' => 3500,
                'stock' => 100,
                'unit' => 'botol',
                'description' => 'Air mineral',
            ],
            [
                'category_id' => 2,
                'code' => 'PRD004',
                'name' => 'Teh Botol',
                'price' => 5000,
                'stock' => 80,
                'unit' => 'botol',
                'description' => 'Minuman teh',
            ],
            [
                'category_id' => 3,
                'code' => 'PRD005',
                'name' => 'Beras 5 Kg',
                'price' => 75000,
                'stock' => 30,
                'unit' => 'pcs',
                'description' => 'Beras kemasan 5 kg',
            ],
            [
                'category_id' => 3,
                'code' => 'PRD006',
                'name' => 'Gula 1 Kg',
                'price' => 18000,
                'stock' => 50,
                'unit' => 'pcs',
                'description' => 'Gula pasir',
            ],
            [
                'category_id' => 4,
                'code' => 'PRD007',
                'name' => 'Sabun Mandi',
                'price' => 5000,
                'stock' => 70,
                'unit' => 'pcs',
                'description' => 'Sabun mandi',
            ],
            [
                'category_id' => 4,
                'code' => 'PRD008',
                'name' => 'Deterjen',
                'price' => 15000,
                'stock' => 40,
                'unit' => 'pcs',
                'description' => 'Deterjen pakaian',
            ],
            [
                'category_id' => 5,
                'code' => 'PRD009',
                'name' => 'Shampoo',
                'price' => 12000,
                'stock' => 50,
                'unit' => 'botol',
                'description' => 'Shampoo',
            ],
            [
                'category_id' => 5,
                'code' => 'PRD010',
                'name' => 'Pasta Gigi',
                'price' => 10000,
                'stock' => 60,
                'unit' => 'pcs',
                'description' => 'Pasta gigi',
            ],
        ]);
    }
}   