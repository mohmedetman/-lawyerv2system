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
        Schema::create('agencies_indices', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->string('agencies_num_en')->nullable();
            $table->string('office_doc_en')->nullable();
            $table->string('agencies_type_en')->nullable();
            $table->string('agencies_num_ar')->nullable();
            $table->string('office_doc_ar')->nullable();
            $table->string('agencies_type_ar')->nullable();
            $table->string('image')->nullable();
            $table->string('date')->nullable();
            $table->string('permission')->default(' ');
            $table->string('status')->default('pending')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agencies_indices');
    }
};
