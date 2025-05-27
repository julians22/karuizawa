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
        Schema::create('down_payment_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->text('response');
            $table->string('down_payment_number')->nullable();
            $table->float('down_payment_amount', 10, 2)->nullable();
            $table->date('down_payment_created_date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('invoice_created_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('down_payment_responses');
    }
};
