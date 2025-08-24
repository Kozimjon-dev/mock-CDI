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
        Schema::create('test_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->string('session_token')->unique();
            $table->enum('current_module', ['listening', 'reading', 'writing', 'completed'])->default('listening');
            $table->timestamp('started_at');
            $table->timestamp('listening_started_at')->nullable();
            $table->timestamp('listening_completed_at')->nullable();
            $table->timestamp('reading_started_at')->nullable();
            $table->timestamp('reading_completed_at')->nullable();
            $table->timestamp('writing_started_at')->nullable();
            $table->timestamp('writing_completed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->boolean('is_fullscreen')->default(false);
            $table->boolean('has_cheated')->default(false);
            $table->text('cheat_attempts')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_sessions');
    }
};
