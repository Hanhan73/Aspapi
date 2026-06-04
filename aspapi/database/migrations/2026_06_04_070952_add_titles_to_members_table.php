<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('front_title')->nullable()->after('full_name')->comment('Gelar depan, e.g. Dr., Prof.');
            $table->string('back_title')->nullable()->after('front_title')->comment('Gelar belakang, e.g. M.Pd., S.E.');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['front_title', 'back_title']);
        });
    }
};