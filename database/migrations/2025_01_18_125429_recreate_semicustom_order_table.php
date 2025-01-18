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
        Schema::table('semi_custom_products', function (Blueprint $table) {
            $table->json('basic_form');
            $table->float('base_price', 20, 2)->default(0);
            $table->float('base_discount', 20, 2)->default(0);
            $table->json('option_form');
            $table->float('option_total', 20, 2)->default(0);
            $table->float('option_additional_price', 20, 2)->default(0);
            $table->float('option_discount', 20, 2)->default(0);
            $table->json('size')->nullable();
            $table->text('base_note')->nullable();
            $table->text('option_note')->nullable();

            $table->dropUnique('semi_custom_products_code_unique');
            $table->dropColumn('details');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('accurate_order_number')->nullable();
            $table->string('accurate_order_id')->nullable();
            $table->string('payment')->nullable()->change();
            $table->string('bank')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop columns
        Schema::table('semi_custom_products', function (Blueprint $table) {
            $table->dropColumn('basic_form');
            $table->dropColumn('base_price');
            $table->dropColumn('base_discount');
            $table->dropColumn('option_form');
            $table->dropColumn('option_total');
            $table->dropColumn('option_additional_price');
            $table->dropColumn('option_discount');
            $table->dropColumn('size');
            $table->dropColumn('base_note');
            $table->dropColumn('option_note');

            $table->string('code')->unique();
            $table->json('details');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('accurate_order_number');
            $table->dropColumn('accurate_order_id');
        });
    }
};
