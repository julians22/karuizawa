<?php

namespace Database\Seeders;

use Database\Migrations\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        $this->disableForeignKeys();;

        // truncate('brands');
        \App\Models\Brand::truncate();

        // create kizoku & karizawa brands

        \App\Models\Brand::create([
            'name' => 'Kizoku',
            'slug' => 'kizoku',
        ]);

        \App\Models\Brand::create([
            'name' => 'Karuizawa',
            'slug' => 'karuizawa',
        ]);

        $this->enableForeignKeys();

        Model::reguard();
    }
}
