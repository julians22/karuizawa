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
        Schema::table('customer_otps', function (Blueprint $table) {
            $table->string('purpose')->default('login')->after('email');
            $table->index(['email', 'purpose', 'verified_at'], 'customer_otps_email_purpose_verified_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_otps', function (Blueprint $table) {
            $table->dropIndex('customer_otps_email_purpose_verified_idx');
            $table->dropColumn('purpose');
        });
    }
};
