<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')
                  ->constrained('seminar_enrollments')
                  ->cascadeOnDelete();
            $table->string('certificate_number')->unique(); // contoh: CERT/ASPAPI/2026/0001
            $table->unsignedTinyInteger('score');           // skor post-test saat lulus
            $table->date('issued_at');
            $table->string('file_path')->nullable();        // path PDF sertifikat yang sudah digenerate
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_certificates');
    }
};