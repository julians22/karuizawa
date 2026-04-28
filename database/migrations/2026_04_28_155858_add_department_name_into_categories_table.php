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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('department_name')->nullable()->default('Apparel')->after('name');
        });

        // Update existing categories with department name
        DB::table('categories')->whereIn('name', ['Parfume', 'Parfum'])
            ->update(['department_name' => 'Parfum']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('department_name');
        });
    }
};
