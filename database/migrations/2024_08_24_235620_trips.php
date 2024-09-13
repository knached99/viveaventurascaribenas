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
            $table->json('tripPhoto');
            $table->text('tripDescription');
            $table->longText('tripActivities')->nullable();
            $table->string('tripLandscape');
            $table->string('tripAvailability');
            $table->datetime('tripStartDate')->nullable(); 
            $table->datetime('tripEndDate')->nullable();
            $table->string('tripPrice');
            $table->integer('num_trips')->default(1);
            $table->boolean('active')->default(false);
            $table->json('tripCosts')->nullable();
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
