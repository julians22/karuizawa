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
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->string('recipient_name')->nullable()->after('label');
            $table->string('recipient_phone')->nullable()->after('recipient_name');
            $table->string('recipient_email')->nullable()->after('recipient_phone');
            $table->string('area_id')->nullable()->after('note');
            $table->string('area_name')->nullable()->after('area_id');
            $table->string('postal_code')->nullable()->after('area_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_name',
                'recipient_phone',
                'recipient_email',
                'area_id',
                'area_name',
                'postal_code',
            ]);
        });
    }
};
