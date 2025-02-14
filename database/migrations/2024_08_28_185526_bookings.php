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
            // $table->string('square_checkout_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone_number');
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->string('preferred_start_date');
            $table->string('preferred_end_date');
            $table->float('amount_captured');
            $table->string('tripID', 255);
            // $table->string('square_product_id');
            // $table->string('square_catalog_object_id');
            // $table->string('square_payment_id');

            $table->foreign('tripID')
            ->references('tripID')
            ->on('trips')
            ->onDelete('cascade');

            // $table->foreign('square_product_id')
            // ->references('square_product_id')
            // ->on('trips')
            // ->onDelete('cascade');
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
