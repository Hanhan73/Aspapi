<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')
                  ->constrained('seminar_enrollments')
                  ->cascadeOnDelete();
            $table->enum('type', ['pre_test', 'post_test']);
            $table->unsignedTinyInteger('score')->nullable();         // 0-100, diisi setelah submit
            $table->boolean('is_passed')->nullable();                 // null = belum selesai
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_attempts');
    }
};