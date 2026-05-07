<?php
// database/migrations/xxxx_update_regions_add_chairman_fields.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            // Kolom yang SUDAH ADA di migration awal (jangan tambah lagi):
            // id, name, slug, province, chairperson, email, phone,
            // address, description, photo, is_active, sort_order, timestamps

            // Kolom BARU yang perlu ditambahkan:
            $table->string('chairman_name')->nullable()->after('province');
            $table->string('chairman_title')->nullable()->after('chairman_name');
            $table->string('period_start', 4)->nullable()->after('chairman_title');
            $table->string('period_end', 4)->nullable()->after('period_start');
            $table->string('website_url')->nullable()->after('period_end');
            $table->string('cover_image')->nullable()->after('photo');
        });
    }

    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->dropColumn([
                'chairman_name', 'chairman_title',
                'period_start', 'period_end',
                'website_url', 'cover_image',
            ]);
        });
    }
};