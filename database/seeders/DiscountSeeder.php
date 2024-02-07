<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Discount;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Discount::create([
            'code' => 'FA111',
            'description' => 'Diskon 10%',
            'type' => 'percentage',
            'amount' => 10,
        ]);

        Discount::create([
            'code' => 'FA222',
            'description' => 'Diskon 50rb untuk barang dengan kode FA4532',
            'type' => 'fixed',
            'amount' => 50000,
            'min_purchase_amount' => 0, // Minimal pembelian tidak diatur
        ]);

        Discount::create([
            'code' => 'FA333',
            'description' => 'Diskon 6% untuk barang diatas 400 ribu',
            'type' => 'percentage',
            'amount' => 6,
            'min_purchase_amount' => 400000,
        ]);

        Discount::create([
            'code' => 'FA444',
            'description' => 'Diskon 5% jika pelanggan membeli di hari selasa jam 13:00 s/d 15:00',
            'type' => 'time_based',
            'amount' => 5,
            'start_time' => '13:00:00',
            'end_time' => '15:00:00',
        ]);
    }
}
