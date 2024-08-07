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
        Schema::create('temporary_case_files', function (Blueprint $table) {
            $table->id();
            $table->text('court_en')->nullable();
            $table->string('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_code')->nullable();
            $table->foreignId('case_id')->references('id')->on('case_files')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->text('user_status_en')->nullable();
            $table->text('enemy_status_en')->nullable();
            $table->text('last_session_en')->nullable();
            $table->string('decision_en')->nullable();
            $table->text('court_ar')->nullable();
            $table->text('user_status_ar')->nullable();
            $table->text('enemy_status_ar')->nullable();
            $table->text('last_session_ar')->nullable();
            $table->string('decision_ar')->nullable();
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
        Schema::dropIfExists('temporary_case_files');
    }
};
