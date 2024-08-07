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
        Schema::create('blog_keywords', function (Blueprint $table) {
            $table->id();
            $table->text('keyword_en')->nullable();
            $table->text('keyword_ar')->nullable();
            $table->foreignId('blog_id')->references('id')->on('blogs')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_keywords');
    }
};
