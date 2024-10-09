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
        Schema::create('judicial_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number');
            $table->date('report_date');
            $table->enum('report_type', ['final', 'archived']);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'archived']);
            $table->string('file')->nullable();
            $table->date('archived_at')->nullable();
            $table->integer('lawyer_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judicial_reports');
    }
};
