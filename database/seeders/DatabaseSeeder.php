<?php

namespace Database\Seeders;

use App\Models\Customer;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder.
 */
class DatabaseSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        Model::unguard();

        $this->disableForeignKeys();

        $this->truncateMultiple([
            'products',
            'stock_movements',
        ]);

        $this->call([
            ProductSeeder::class,
            StockMovementSeeder::class,
            CustomerSeeder::class
        ]);

        $this->enableForeignKeys();

        // $this->truncateMultiple([
        //     'activity_log',
        //     'failed_jobs',
        // ]);

        // $this->call(AuthSeeder::class);
        // $this->call(AnnouncementSeeder::class);
        // $this->call(StoreSeeder::class);

        Model::reguard();
    }
}
