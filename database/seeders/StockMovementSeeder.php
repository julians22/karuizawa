<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Add stock movements for each product
            StockMovement::create([
                'product_id' => $product->id,
                'quantity' => rand(50, 100),
                'type' => 'addition',
                'stock_opname_date' => now(),
            ]);

            StockMovement::create([
                'product_id' => $product->id,
                'quantity' => rand(10, 50),
                'type' => 'subtraction',
                'stock_opname_date' => now(),
            ]);
        }
    }
}
