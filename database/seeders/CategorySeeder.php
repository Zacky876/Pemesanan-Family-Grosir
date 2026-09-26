<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Makanan',
                'description' => 'Produk makanan',
            ],
            [
                'name' => 'Minuman',
                'description' => 'Produk minuman',
            ],
            [
                'name' => 'Sembako',
                'description' => 'Kebutuhan pokok',
            ],
            [
                'name' => 'Kebersihan',
                'description' => 'Produk kebersihan',
            ],
            [
                'name' => 'Perawatan',
                'description' => 'Produk perawatan tubuh',
            ],
        ]);
    }
}