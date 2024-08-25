<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->uuid('testimonialID')->primary();
            $table->string('name');
            $table->string('email')->nullable();
            $table->uuid('tripID');
            $table->string('trip_date');
            $table->integer('trip_rating');
            $table->longText('testimonial');
            $table->boolean('consent');
            $table->string('testimonial_approval_status');
            $table->timestamps();

            $table->foreign('tripID')
                  ->references('tripID')
                  ->on('trips')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
