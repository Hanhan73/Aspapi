<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MariaDB/MySQL: modify enum dengan ALTER TABLE langsung
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin','admin','bendahara','aspapi_daerah','anggota','guest') NOT NULL DEFAULT 'guest'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','bendahara','aspapi_daerah','anggota','guest') NOT NULL DEFAULT 'guest'");
    }
};