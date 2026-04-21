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
        Schema::create('semi_custom_outer_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->json('basic_form');
            $table->float('base_price', 20, 2)->default(0);
            $table->float('base_discount', 20, 2)->default(0);
            $table->json('option_form')->nullable();
            $table->float('option_total', 20, 2)->default(0);
            $table->float('option_additional_price', 20, 2)->default(0);
            $table->float('option_discount', 20, 2)->default(0);
            $table->json('size')->nullable();
            $table->text('base_note')->nullable();
            $table->text('option_note')->nullable();
            $table->date('handling_date')->nullable();
            $table->enum('status', ['processing', 'finish'])->default('processing');
            $table->timestamp('finish_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semi_custom_outer_products');
    }
};
