<?php
// database/migrations/xxxx_create_seminar_materials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seminar_id')->constrained()->cascadeOnDelete();
            $table->string('label');          // nama/judul materi, misal "Modul 1 — Pengantar"
            $table->string('url');            // link Google Drive
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('seminars')->whereNotNull('material_url')->get()
    ->each(function ($s) {
        DB::table('seminar_materials')->insert([
            'seminar_id' => $s->id,
            'label'      => 'Materi Utama',
            'url'        => $s->material_url,
            'sort_order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    });

        Schema::table('seminars', function (Blueprint $table) {
            $table->dropColumn('material_url');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_materials');

        Schema::table('seminars', function (Blueprint $table) {
            $table->string('material_url')->nullable()->after('thumbnail');
        });
    }
};