<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ASPAPI — Complete Database Schema
 * Run: php artisan migrate
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. USERS ──────────────────────────────────────────────────


        // ── 2. MEMBERS (Anggota) ──────────────────────────────────────
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Identitas
            $table->string('member_number')->unique()->nullable()->comment('Nomor anggota, diisi setelah diverifikasi');
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('nik', 20)->nullable()->comment('Nomor Induk Kependudukan');

            // Jenis anggota
            $table->enum('member_type', [
                'biasa',          // Sarjana / mahasiswa administrasi perkantoran
                'luar_biasa',     // Praktisi non-sarjana administrasi perkantoran
                'kehormatan',     // Tokoh yang berjasa
            ])->default('biasa');

            // Data akademik / profesional
            $table->string('institution')->nullable()->comment('Universitas / Perusahaan');
            $table->string('study_program')->nullable();
            $table->string('position')->nullable()->comment('Jabatan / Pekerjaan');
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('photo')->nullable()->comment('Path foto profil');

            // Status
            $table->enum('status', ['pending', 'active', 'inactive', 'rejected'])->default('pending');
            $table->date('registered_at')->nullable();
            $table->date('active_until')->nullable()->comment('Masa berlaku keanggotaan');

            // Iuran
            $table->boolean('dues_paid')->default(false);
            $table->date('dues_paid_at')->nullable();
            $table->string('dues_receipt')->nullable()->comment('Path bukti bayar');

            $table->timestamps();
            $table->softDeletes();
        });

        // ── 3. REGIONS (ASPAPI Daerah) ────────────────────────────────
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // Nama wilayah
            $table->string('slug')->unique();
            $table->string('province');
            $table->string('chairperson')->nullable();      // Nama ketua
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('photo')->nullable();            // Foto pengurus / gedung
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // ── 4. BOARDS (Pengurus Pusat) ────────────────────────────────
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');                     // Jabatan
            $table->string('position_category')->nullable() // Kategori jabatan
                  ->comment('Ketua Umum, Sekretaris, Bendahara, Bidang, dsb');
            $table->string('institution')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('photo')->nullable();
            $table->string('period')->nullable()            // Periode, e.g. "2022-2026"
                  ->comment('Periode kepengurusan');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // ── 5. ADVISORS (Dewan Penasihat) ─────────────────────────────
        Schema::create('advisors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();            // Gelar / Prof., Dr., dst
            $table->string('position')->nullable();         // Jabatan dalam dewan
            $table->string('institution')->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // ── 6. EXPERTS (Dewan Pakar) ──────────────────────────────────
        Schema::create('experts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('expertise')->nullable();        // Bidang keahlian
            $table->string('institution')->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // ── 7. NEWS (Berita) ──────────────────────────────────────────
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('body');
            $table->string('thumbnail')->nullable();
            $table->string('category')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // ── 8. BLOGS ──────────────────────────────────────────────────
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('body');
            $table->string('thumbnail')->nullable();
            $table->string('category')->nullable();
            $table->string('author_name')->nullable();      // Nama penulis (bisa tamu)
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // ── 9. DOCUMENTS (Download) ───────────────────────────────────
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');                    // Path file
            $table->string('file_name')->nullable();        // Nama asli file
            $table->string('file_type', 20)->nullable();    // pdf, docx, xlsx, dst
            $table->unsignedBigInteger('file_size')->nullable()->comment('Bytes');
            $table->string('category')->nullable();         // AD/ART, Peraturan, Formulir, dsb
            $table->boolean('is_public')->default(true);    // false = hanya untuk anggota
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('downloads')->default(0);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('news');
        Schema::dropIfExists('experts');
        Schema::dropIfExists('advisors');
        Schema::dropIfExists('boards');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('members');
        Schema::dropIfExists('users');
    }
};
