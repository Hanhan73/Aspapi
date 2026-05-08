<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Kolom baru untuk data pribadi
            $table->string('birth_place')->nullable()->after('nik');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->string('last_education')->nullable()->after('gender');
            // education: SD, SMP, SMA, D3, S1, S2, S3, Profesi, Lainnya

            // Pisah pekerjaan dari institusi
            // 'institution' tetap ada (nama universitas/instansi)
            // 'study_program' di-rename jadi 'occupation' (pekerjaan/profesi)
            // 'position' tetap ada (jabatan di instansi)
            $table->renameColumn('study_program', 'occupation');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['birth_place', 'birth_date', 'last_education']);
            $table->renameColumn('occupation', 'study_program');
        });
    }
};