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
        Schema::create('booking_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            
            // Pickup
            $table->string('pickup_name');
            $table->string('pickup_phone');
            $table->string('pickup_address');
            $table->string('pickup_city');
            $table->string('pickup_province');
            $table->string('pickup_zip');
            
            // Delivery
            $table->string('delivery_name');
            $table->string('delivery_phone');
            $table->string('delivery_address');
            $table->string('delivery_city');
            $table->string('delivery_province');
            $table->string('delivery_zip');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_locations');
    }
};
