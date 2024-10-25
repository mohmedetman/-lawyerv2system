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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('lawyer_id')->unique();
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->text('personal_id')->nullable();
            $table->string('gender')->nullable();
            $table->string('litigationDegree_en')->nullable();
            $table->string('litigationDegree_ar')->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
