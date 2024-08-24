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
        Schema::create('power_attrotneys', function (Blueprint $table) {
            $table->id();
            $table->integer('numeric_classification')->nullable();
            $table->string('alphabetic_classification', 5)->nullable();
            $table->string('type')->nullable();
            $table->integer('customer_id');
            $table->string('notes')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('power_attrotneys');
    }
};
