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
        Schema::create('bookings', function(Blueprint $table){
            $table->uuid('bookingID')->primary();
            $table->string('stripe_checkout_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone_number');
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->float('amount_captured');
            $table->uuid('tripID');
            $table->string('stripe_product_id');

            $table->foreign('tripID')
            ->references('tripID')
            ->on('trips')
            ->onDelete('cascade');

            $table->foreign('stripe_product_id')
            ->references('stripe_product_id')
            ->on('trips')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
