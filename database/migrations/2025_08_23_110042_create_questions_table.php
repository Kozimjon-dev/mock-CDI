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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('module', ['listening', 'reading', 'writing']);
            $table->integer('part')->default(1); // Part 1, 2, 3, 4 for listening, 1, 2, 3 for reading
            $table->enum('type', ['multiple_choice', 'gap_filling', 'select_options']);
            $table->text('question_text');
            $table->json('options')->nullable(); // For multiple choice and select options
            $table->json('correct_answers'); // Can be multiple for gap filling
            $table->integer('points')->default(1);
            $table->integer('order')->default(0);
            $table->json('metadata')->nullable(); // Additional data like audio timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
