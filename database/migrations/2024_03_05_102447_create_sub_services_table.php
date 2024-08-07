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
        Schema::create('sub_services', function (Blueprint $table) {
            $table->id();
            $table->text('title_en')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_url')->nullable();
            $table->text('description_en')->nullable();
            $table->text('title_ar')->nullable();
            $table->text('description_ar')->nullable();
            $table->foreignId('service_id')->references('id')->on('services')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_services');
    }
};
