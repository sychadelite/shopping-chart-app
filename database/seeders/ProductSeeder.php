<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['code' => 'FA4532', 'name' => 'Product 1', 'price' => 455000.00, 'quantity' => 100],
            ['code' => 'FA3518', 'name' => 'Product 2', 'price' => 336000.00, 'quantity' => 50],
            ['code' => 'FA6666', 'name' => 'Product 3', 'price' => 150000.00, 'quantity' => 75],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
