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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['audio', 'text', 'image']);
            $table->enum('module', ['listening', 'reading', 'writing']);
            $table->integer('part')->default(1);
            $table->string('title');
            $table->text('content')->nullable(); // For text materials
            $table->string('file_path')->nullable(); // For audio/image files
            $table->string('file_name')->nullable();
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('order')->default(0);
            $table->json('metadata')->nullable(); // Additional data like audio duration
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
