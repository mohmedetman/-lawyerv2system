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
        Schema::create('lawyers', function (Blueprint $table) {
            $table->id();
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('password');
            $table->string('code')->nullable();
            $table->integer('admin_id');
            $table->integer('status')->default(0);
            $table->string('bio_ar')->nullable();
            $table->string('bio_en')->nullable();
            $table->integer('department_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyers');
    }
};
