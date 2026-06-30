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
        Schema::table('semi_custom_outer_products', function (Blueprint $table){
            $table->text('address')->nullable();
        });
        Schema::table('semi_custom_products', function (Blueprint $table){
            $table->text('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('semi_custom_outer_products', function (Blueprint $table) {
            $table->dropColumn('address');
        });
        Schema::table('semi_custom_products', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};
