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
        Schema::table('order_items', function(Blueprint $table){
            $table->decimal('discount', 15, 2)->default(0);
            $table->json('discount_detail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function(Blueprint $table){
            $table->dropColumn('discount');
            $table->dropColumn('discount_detail');
        });
    }
};
