<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->string('tripID', 255)->primary();
            // $table->string('square_catalog_object_id')->unqiue();
            $table->string('tripLocation');
            $table->json('tripPhoto');
            $table->text('tripDescription');
            $table->longText('tripActivities')->nullable();
            $table->json('tripLandscape');
            $table->string('tripAvailability');
            $table->datetime('tripStartDate')->nullable(); 
            $table->datetime('tripEndDate')->nullable();
            $table->string('tripPrice');
            $table->integer('num_trips')->default(1);
            $table->boolean('active')->default(false);
            $table->json('tripCosts')->nullable();
            $table->string('slug')->unique()->nullable(); // URL slug replacing uuid for SEO
            // $table->string('square_product_id')->unique(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};

