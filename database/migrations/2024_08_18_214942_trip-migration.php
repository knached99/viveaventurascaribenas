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
        Schema::create('trips', function (Blueprint $table){
           $table->uuid('tripID')->primary();
           $table->string('tripLocation');
           $table->string('tripPhoto');
           $table->text('tripDescription');
           $table->string('tripLandscape');
           $table->string('tripAvailability');
           $table->datetime('tripStartDate'); 
           $table->datetime('tripEndDate');
           $table->string('tripPrice');
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
