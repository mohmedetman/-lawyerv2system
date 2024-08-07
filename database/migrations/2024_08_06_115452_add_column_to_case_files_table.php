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
        Schema::table('case_files', function (Blueprint $table) {
            $table->integer('lawyer_id')->nullable();
            $table->string('model_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_files', function (Blueprint $table) {
            $table->dropColumn(['lawyer_id','model_type']);
        });
    }
};
