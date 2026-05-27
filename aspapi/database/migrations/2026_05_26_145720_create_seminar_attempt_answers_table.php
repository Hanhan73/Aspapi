<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')
                  ->constrained('seminar_attempts')
                  ->cascadeOnDelete();
            $table->foreignId('question_id')
                  ->constrained('seminar_questions')
                  ->cascadeOnDelete();
            $table->enum('answer', ['a', 'b', 'c', 'd', 'e'])->nullable(); // null = tidak dijawab
            $table->boolean('is_correct')->nullable();
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_attempt_answers');
    }
};