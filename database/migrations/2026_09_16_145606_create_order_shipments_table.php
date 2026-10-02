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
        Schema::create('order_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('customer_address_id')->nullable()->constrained('customer_addresses')->nullOnDelete();

            // Recipient snapshot - stored independently from customer_addresses so
            // shipment data stays accurate even if the address is later edited/deleted.
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('recipient_address');
            $table->string('area_id')->nullable();
            $table->string('area_name')->nullable();
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Selected courier/rate
            $table->string('courier_company')->nullable();
            $table->string('courier_type')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('courier_service_name')->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('insurance_cost', 10, 2)->default(0);

            // Biteship data
            $table->string('biteship_order_id')->nullable()->unique();
            $table->string('biteship_tracking_id')->nullable();
            $table->string('waybill_id')->nullable();
            $table->string('status')->default('pending');
            $table->text('note')->nullable();
            $table->json('raw_request')->nullable();
            $table->json('raw_response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_shipments');
    }
};
