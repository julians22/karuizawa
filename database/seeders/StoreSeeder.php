<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'name' => 'Ashta',
            'code' => 'AST',
            'address' => 'ASHTA District 8 Lantai UG Unit UG-18 Jalan Senopati No 8, Senayan Kecamatan Kebayoran Baru Jakarta Selatan 12190',
            'accurate_alias' => '350'
        ]);

        Store::create([
            'name' => 'PIK',
            'code' => 'PIK',
            'address' => 'PIK AVENUE Jl. Pantai Indah Kapuk Boulevard, RT.6/RW.2, Kamal Muara, Kec. Penjaringan, Jkt Utara, Daerah Khusus Ibukota Jakarta 14470',
            'accurate_alias' => '450'
        ]);
    }
}
