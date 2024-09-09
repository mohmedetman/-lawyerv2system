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
        Schema::create('judgments', function (Blueprint $table) {
            $table->id();
            $table->integer('case_id');
            $table->enum('judgment_type', ['primary', 'absentia', 'final']);
            $table->string('issued_by');
            $table->date('date_issued');
            $table->text('details');
            $table->boolean('appeal_possible')->default(true);
            $table->boolean('is_in_absentia')->default(false);
            $table->date('notification_date')->nullable();
            $table->string('default_party')->nullable();
            $table->date('appeal_deadline')->nullable();
            $table->boolean('is_final')->default(false);
            $table->date('finalized_on')->nullable();
            $table->enum('execution_status', ['pending', 'executed', 'delayed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judgments');
    }
};
