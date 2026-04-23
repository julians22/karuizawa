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
        Schema::table('target_settings', function (Blueprint $table) {
            $table->string('semicustom_name')->nullable()->after('is_semicustom');
        });

        DB::table('target_settings')->where('is_semicustom', true)->update(['semicustom_name' => 'Semi Custom']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('target_settings', function (Blueprint $table) {
            $table->dropColumn('semicustom_name');
        });
    }
};
