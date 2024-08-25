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
        Schema::create('case_files_11', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->text('court_en')->nullable();
            $table->text('enemy_status_en')->nullable();
            $table->text('last_session_en')->nullable();
            $table->string('decision_en')->nullable();
            $table->text('court_ar')->nullable();
            $table->text('user_status_ar')->nullable();
            $table->text('enemy_status_ar')->nullable();
            $table->text('last_session_ar')->nullable();
            $table->string('decision_ar')->nullable();
            $table->string('permission')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('user_type')->nullable();
            $table->unsignedBigInteger('case_type_id'); // Foreign key to case_types
            $table->unsignedBigInteger('case_degree_id'); // Foreign key to case_degrees
            $table->foreign('case_type_id')->references('id')->on('case_types')->onDelete('cascade');
            $table->foreign('case_degree_id')->references('id')->on('case_degrees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_files');
    }
};
