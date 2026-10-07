<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kizoku's Midtrans order id (KZK-...) so payment webhooks can find the order.
            $table->string('midtrans_order_id', 100)->nullable()->unique()->after('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['midtrans_order_id']);
            $table->dropColumn('midtrans_order_id');
        });
    }
};
