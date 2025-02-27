<?php

use Database\Migrations\Traits\DisableForeignKeys;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use DisableForeignKeys;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // need to truncate the table first
        $this->truncateRelated();

        // Add uuid column into orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique();
            $table->string('order_number')->after('uuid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }

    private function truncateRelated(): void
    {
        // Disable foreign key check for this operation
        $this->disableForeignKeys();

        DB::table('payments')->truncate();
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();

        // Enable foreign key check
        $this->enableForeignKeys();
    }
};
