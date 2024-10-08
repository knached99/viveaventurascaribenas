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
        Schema::create('photo_gallery', function (Blueprint $table){

        $table->uuid('photoID')->primary();
        $table->string('photoLabel')->nullable();
        $table->text('photoDescription')->nullable();
        $table->json('photos');
        $table->uuid('tripID');

        $table->foreign('tripID')
        ->references('tripID')
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
        // Discard tablespace before dropping the table, if it exists
        DB::statement('ALTER TABLE photo_gallery DISCARD TABLESPACE');
        
        Schema::dropIfExists('photo_gallery');
    }

};
