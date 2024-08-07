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
        Schema::create('bailiffs_papers', function (Blueprint $table) {
            $table->id();
            $table->text('bailiffs_pen_en')->nullable();
            $table->text('bailiffs_pen_ar')->nullable();
            $table->string('user_code')->nullable();
            $table->string('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('bailiffs_num')->nullable();
            $table->string('delivery_time')->nullable();
            $table->string('session_time')->nullable();
            $table->string('announcment_time')->nullable();
            $table->string('bailiff_reply')->nullable();
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
        Schema::dropIfExists('bailiffs_papers');
    }
};
