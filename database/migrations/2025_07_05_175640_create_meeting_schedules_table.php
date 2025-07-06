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
        Schema::create('meeting_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legal_case_id')->constrained('legal_cases')->cascadeOnDelete();
            $table->string('about');
            $table->dateTime('date_time');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'cancelled', 'finished'])->default('pending');
            $table->enum('file_collection', ['yes', 'no'])->default('no');
            $table->date('file_submission_deadline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_schedules');
    }
};
