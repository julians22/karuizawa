<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promos = [
            [
                'name' => '10%',
                'value' => '10',
            ],
            [
                'name' => '20%',
                'value' => '20',
            ],
            [
                'name' => '30%',
                'value' => '30',
            ],
        ];

        foreach ($promos as $promo) {
            \App\Models\Promo::create($promo);
        }
    }
}
