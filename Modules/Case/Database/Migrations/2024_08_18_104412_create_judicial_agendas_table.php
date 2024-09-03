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
        Schema::create('judicial_agendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->datetime('next_agenda_date');
            $table->datetime('previous_agenda_date');
            $table->integer('employee_id')->nullable();
            $table->integer('lawyer_id');
            $table->string('model_type');
            $table->string('actions')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judicial_agendas');
    }
};
