<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Discount::create([
            'name' => '1st anniversary',
            'description' => '20% off',
            'type' => 'percentage',
            'value' => 20,
            'status' => 'active',
            'expired_date' => '2024-03-07'
        ]);

        \App\Models\Discount::create([
            'name' => 'Happy Hour',
            'description' => '10% off',
            'type' => 'percentage',
            'value' => 10,
            'status' => 'active',
            'expired_date' => '2024-03-10'
        ]);

        \App\Models\Discount::create([
            'name' => 'Fasting Discount',
            'description' => '15% off',
            'type' => 'percentage',
            'value' => 15,
            'status' => 'active',
            'expired_date' => '2024-03-11'
        ]);
    }
}
