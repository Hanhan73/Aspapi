<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Update users table — tambah role baru ──
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'bendahara', 'aspapi_daerah', 'anggota', 'guest'])
                  ->default('guest')->change();
            $table->boolean('email_verified')->default(false)->after('email_verified_at');
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete()->after('role');
        });

        // ── Provinces ──
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('name');
            $table->timestamps();
        });

        // ── Cities ──
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('provinces')->cascadeOnDelete();
            $table->string('code', 2);
            $table->string('name');
            $table->timestamps();
        });

        // ── Update members table ──
        Schema::table('members', function (Blueprint $table) {
            // Tipe anggota lama/baru
            $table->enum('registration_type', ['baru', 'lama'])->default('baru')->after('member_type');
            // Status verifikasi biodata oleh admin
            $table->enum('biodata_status', ['pending', 'verified', 'rejected'])->default('pending')->after('status');
            $table->text('biodata_reject_reason')->nullable()->after('biodata_status');
            // Klaim anggota lama
            $table->boolean('claims_old_member')->default(false)->after('registration_type');
            $table->year('claimed_join_year')->nullable()->after('claims_old_member');
            // Province & city untuk nomor anggota
            $table->foreignId('province_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->enum('gender', ['L', 'P'])->nullable();
            // Untuk batch dari aspapi daerah
            $table->boolean('is_batch')->default(false);
            $table->foreignId('registered_by_region_id')->nullable()->constrained('regions')->nullOnDelete();
        });

        // ── Payment Batches (kolektif dari aspapi daerah) ──
        Schema::create('payment_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->string('receipt_path')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->unsignedInteger('member_count')->default(0);
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('reject_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->year('payment_year');
            $table->timestamps();
        });

        // ── Payments ──
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->enum('type', ['uang_pangkal', 'iuran_tahunan']);
            $table->enum('payment_method', ['mandiri', 'kolektif'])->default('mandiri');
            $table->decimal('amount', 12, 2);
            $table->string('receipt_path')->nullable()->comment('Bukti transfer');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('reject_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->year('payment_year')->nullable()->comment('Tahun iuran');
            // Untuk kolektif
            $table->foreignId('batch_id')->nullable()->constrained('payment_batches')->nullOnDelete();
            $table->timestamps();
        });

        // ── Member Verification Logs ──
        Schema::create('member_verification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('verified_by')->constrained('users')->cascadeOnDelete();
            $table->enum('action', ['approve_biodata', 'reject_biodata', 'approve_old_member', 'reject_old_member', 'approve_payment', 'reject_payment']);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_verification_logs');
        Schema::dropIfExists('payment_batches');
        Schema::dropIfExists('payments');
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['registration_type', 'biodata_status', 'biodata_reject_reason', 'claims_old_member', 'claimed_join_year', 'province_id', 'city_id', 'gender', 'is_batch', 'registered_by_region_id']);
        });
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verified', 'region_id']);
        });
    }
};