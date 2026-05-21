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
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            
            $table->string('luggage_type');
            $table->integer('quantity');
            $table->decimal('weight', 8, 2); // kg
            $table->decimal('distance', 8, 2); // km
            $table->string('dimensions')->nullable();
            $table->text('description')->nullable(); // Mapped from special_instructions
            $table->string('image_path')->nullable(); // Mapped from luggage_image_path
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
