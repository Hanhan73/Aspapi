<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Satu baris = satu anggota mengambil satu seminar dalam satu periode aktif.
        // Kuota 3 seminar dihitung per (member_id, membership_period_start).
        Schema::create('seminar_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seminar_id')->constrained()->cascadeOnDelete();

            // Catat awal periode keanggotaan saat mendaftar seminar,
            // dipakai untuk batas kuota 3 seminar/periode.
            $table->date('membership_period_start');

            // Status perjalanan seminar anggota
            $table->enum('status', [
                'enrolled',          // baru mendaftar, belum pre-test
                'pre_test_done',     // pre-test selesai, bisa akses materi
                'material_read',     // materi sudah ditandai selesai dibaca
                'post_test_done',    // post-test selesai, tapi belum/tidak lulus
                'completed',         // lulus post-test, sertifikat tersedia
            ])->default('enrolled');

            $table->timestamps();

            $table->unique(['member_id', 'seminar_id']); // 1 anggota hanya bisa ambil 1x per seminar
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_enrollments');
    }
};