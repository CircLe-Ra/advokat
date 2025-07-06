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
        Schema::create('legal_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('lawyer_id')->nullable()->constrained('lawyers')->onDelete('cascade');
            $table->string('number', 100);
            $table->enum('type', ['civil', 'criminal']);
            $table->string('title',100);
            $table->text('summary');
            $table->text('chronology');
            $table->enum('status', ['draft','pending', 'revision','revised','verified','rejected','accepted', 'closed'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_cases');
    }
};
