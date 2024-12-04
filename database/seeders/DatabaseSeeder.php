<?php

namespace Database\Seeders;

use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder.
 */
class DatabaseSeeder extends Seeder
{
    use TruncateTable;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        Model::unguard();

        // $this->truncateMultiple([
        //     'products',
        //     'stock_movements',
        // ]);

        // $this->call([
        //     ProductSeeder::class,
        //     StockMovementSeeder::class,
        // ]);

        $this->truncateMultiple([
            'activity_log',
            'failed_jobs',
        ]);

        $this->call(AuthSeeder::class);
        $this->call(AnnouncementSeeder::class);
        $this->call(StoreSeeder::class);

        Model::reguard();
    }
}
