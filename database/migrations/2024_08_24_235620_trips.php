<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->uuid('tripID')->primary();
            $table->string('stripe_product_id')->unique();
            $table->string('tripLocation');
            $table->string('tripPhoto');
            $table->text('tripDescription');
            $table->longText('tripActivities')->nullable();
            $table->string('tripLandscape');
            $table->string('tripAvailability');
            $table->datetime('tripStartDate'); 
            $table->datetime('tripEndDate');
            $table->string('tripPrice');
           // $table->uuid('testimonial_id')->nullable();

            // $table->foreign('testimonial_id')
            //       ->references('testimonialID')
            //       ->on('testimonials')
            //       ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
