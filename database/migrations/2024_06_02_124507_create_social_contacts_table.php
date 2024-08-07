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
        Schema::create('social_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('facebook_url')->nullable();
            $table->string('instgram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('whatsapp_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_contacts');
    }
};
