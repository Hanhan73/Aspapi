<?php
// database/migrations/2026_xx_xx_add_notification_email_to_users.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Email penerima notifikasi (bisa beda dari email login)
            $table->string('notification_email')->nullable()->after('email');
        });

        Schema::table('regions', function (Blueprint $table) {
            // Email notifikasi khusus untuk admin daerah (bisa beda dari user login)
            $table->string('notification_email')->nullable()->after('email');
        });

        Schema::table('members', function (Blueprint $table) {
            // Pilihan region oleh anggota sendiri saat isi biodata
            // (bisa beda dari registered_by_region_id yang di-set batch)
            // Kita reuse registered_by_region_id — tidak perlu kolom baru
            // Hanya perlu allow null saat awal daftar
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('notification_email');
        });
        Schema::table('regions', function (Blueprint $table) {
            $table->dropColumn('notification_email');
        });
    }
};