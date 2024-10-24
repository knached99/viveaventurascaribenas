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
        Schema::create('reservations', function (Blueprint $table){
            $table->uuid('reservationID')->primary();
            $table->string('stripe_product_id');
            $table->uuid('tripID');
            $table->string('name');
            $table->string('email');
            $table->string('phone_number');
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('preferred_start_date');
            $table->string('preferred_end_date');
            $table->string('zip_code');
            $table->timestamps();

            $table->foreign('tripID')
            ->references('tripID')
            ->on('trips')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schmea::dropIfExists('reservations');
    }
};
