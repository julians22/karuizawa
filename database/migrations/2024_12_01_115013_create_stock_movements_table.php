<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('type', ['addition', 'subtraction']);
            $table->date('stock_opname_date')->nullable();
            $table->timestamps();
        });

        // Create a view for stock reporting
        DB::statement('
            CREATE VIEW product_stock_report AS
            SELECT
                p.id AS product_id,
                p.product_name,
                p.description,
                p.price,
                SUM(CASE WHEN sm.type = "addition" THEN sm.quantity ELSE 0 END) AS total_added,
                SUM(CASE WHEN sm.type = "subtraction" THEN sm.quantity ELSE 0 END) AS total_sold,
                (SUM(CASE WHEN sm.type = "addition" THEN sm.quantity ELSE 0 END) -
                 SUM(CASE WHEN sm.type = "subtraction" THEN sm.quantity ELSE 0 END)) AS current_stock,
                MAX(sm.stock_opname_date) AS last_stock_opname_date,
                p.created_at,
                p.updated_at
            FROM products p
            LEFT JOIN stock_movements sm ON p.id = sm.product_id
            GROUP BY p.id, p.product_name, p.description, p.price, p.created_at, p.updated_at
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the view first
        DB::statement('DROP VIEW IF EXISTS product_stock_report');

        Schema::dropIfExists('stock_movements');
    }
};
