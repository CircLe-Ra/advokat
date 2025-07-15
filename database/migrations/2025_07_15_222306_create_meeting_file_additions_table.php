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
        Schema::create('meeting_file_additions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_schedule_id')->constrained('meeting_schedules')->onDelete('cascade');
            $table->text('file');
            $table->string('type',10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_file_additions');
    }
};
