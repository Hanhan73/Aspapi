<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('activity_type')->default('Kegiatan ASPAPI');
            $table->date('event_date');
            $table->date('event_date_end')->nullable();
            $table->string('place')->nullable();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('agenda_id')->nullable()->constrained('agendas')->nullOnDelete();
            $table->foreignId('seminar_id')->nullable()->constrained('seminars')->nullOnDelete();
            $table->text('description')->nullable();
            // Sengaja tanpa FK (hindari relasi melingkar albums <-> photos).
            // Konsistensi dijaga di model GalleryPhoto (deleting).
            $table->unsignedBigInteger('cover_photo_id')->nullable()->index();
            $table->boolean('is_published')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_published', 'event_date']);
        });

        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_album_id')->constrained('gallery_albums')->cascadeOnDelete();
            $table->string('path');
            $table->string('thumb_path');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('thumb_width')->nullable();
            $table->unsignedInteger('thumb_height')->nullable();
            $table->string('note', 500)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['gallery_album_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_photos');
        Schema::dropIfExists('gallery_albums');
    }
};
