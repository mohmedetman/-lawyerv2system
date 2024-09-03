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
        Schema::create('power_attorneys', function (Blueprint $table) {
            $table->id();
            $table->string('numeric_classification');
            $table->string('alphabetic_classification');
            $table->string('image');
            $table->string('notes');
            $table->integer('customer_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('power_attorneys');
    }
};
