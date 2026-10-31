<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialty_id')->constrained()->restrictOnDelete();
            $table->text('prompt');
            $table->json('options');
            $table->unsignedTinyInteger('correct_option');
            $table->text('explanation');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialty_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->unsignedSmallInteger('duration_minutes')->default(30);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
        Schema::create('exam_question', function (Blueprint $table) {
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->restrictOnDelete();
            $table->primary(['exam_id', 'question_id']);
        });
        Schema::create('attempts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('exam_id')->constrained()->restrictOnDelete();
            $table->char('token_hash', 64);
            $table->json('snapshot');
            $table->json('answers')->nullable();
            $table->unsignedSmallInteger('score')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('attempts');
        Schema::dropIfExists('exam_question');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('questions');
    }
};
