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
        Schema::create('visitors', function(Blueprint $table){
            $table->uuid('visitor_uuid')->primary();
            $table->string('visitor_ip_address');
            $table->string('visitor_user_agent');
            $table->text('visited_url')->nullable();
            $table->text('visitor_referrer')->nullable();
            $table->timestamp('visited_at');
            $table->longText('unique_identifier')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
