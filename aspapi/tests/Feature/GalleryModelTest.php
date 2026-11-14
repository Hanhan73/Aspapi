<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryModelTest extends TestCase
{
    use RefreshDatabase;

    private function album(array $attrs = []): GalleryAlbum
    {
        return GalleryAlbum::create(array_merge([
            'title'      => 'Rapat Kerja Nasional',
            'slug'       => 'rakernas',
            'event_date' => '2026-02-16',
        ], $attrs));
    }

    public function test_date_label_formats_in_indonesian(): void
    {
        $this->assertSame('16 Februari 2026', $this->album()->dateLabel());
        $this->assertSame('16–18 Februari 2026', $this->album(['slug' => 'a', 'event_date_end' => '2026-02-18'])->dateLabel());
        $this->assertSame('30 Januari – 2 Februari 2026', $this->album(['slug' => 'b', 'event_date' => '2026-01-30', 'event_date_end' => '2026-02-02'])->dateLabel());
        $this->assertSame('30 Desember 2025 – 2 Januari 2026', $this->album(['slug' => 'c', 'event_date' => '2025-12-30', 'event_date_end' => '2026-01-02'])->dateLabel());
    }

    public function test_caption_lines_hide_empty_fields(): void
    {
        $album = $this->album();
        $this->assertSame(['16 Februari 2026 · Kegiatan ASPAPI'], $album->captionLines());

        $region = Region::create(['name' => 'ASPAPI Jawa Barat', 'province' => 'Jawa Barat']);
        $album->update(['place' => 'Bandung', 'region_id' => $region->id, 'activity_type' => 'Rapat Kerja']);
        $photo = new GalleryPhoto(['note' => 'Sambutan ketua']);

        $this->assertSame([
            '16 Februari 2026 · Rapat Kerja',
            'Tempat: Bandung · Daerah: ASPAPI Jawa Barat',
            'Sambutan ketua',
        ], $album->fresh()->captionLines($photo));
    }

    public function test_caption_component_escapes_text(): void
    {
        $album = $this->album(['place' => '<script>alert(1)</script>']);

        $html = (string) $this->blade('<x-gallery-caption :album="$album" />', ['album' => $album]);

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function test_deleting_album_removes_photo_files_and_rows(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/1/a.webp', 'x');
        Storage::disk('public')->put('gallery/1/thumbs/a.webp', 'x');

        $album = $this->album();
        $photo = $album->photos()->create(['path' => 'gallery/1/a.webp', 'thumb_path' => 'gallery/1/thumbs/a.webp']);
        $album->update(['cover_photo_id' => $photo->id]);

        $album->delete();

        Storage::disk('public')->assertMissing(['gallery/1/a.webp', 'gallery/1/thumbs/a.webp']);
        $this->assertDatabaseCount('gallery_photos', 0);
    }

    public function test_deleting_cover_photo_clears_cover_and_falls_back(): void
    {
        Storage::fake('public');
        $album = $this->album();
        $p1 = $album->photos()->create(['path' => 'a.webp', 'thumb_path' => 'ta.webp', 'sort_order' => 1]);
        $p2 = $album->photos()->create(['path' => 'b.webp', 'thumb_path' => 'tb.webp', 'sort_order' => 2]);
        $album->update(['cover_photo_id' => $p2->id]);

        $p2->delete();

        $album->refresh();
        $this->assertNull($album->cover_photo_id);
        $this->assertSame($p1->id, $album->coverPhoto()->id);
    }

    public function test_unique_slug_and_published_scope(): void
    {
        $this->album(['is_published' => true]);
        $this->assertSame('rapat-kerja-nasional', GalleryAlbum::uniqueSlug('Rapat Kerja Nasional'));
        $this->album(['slug' => 'rapat-kerja-nasional']);
        $this->assertSame('rapat-kerja-nasional-2', GalleryAlbum::uniqueSlug('Rapat Kerja Nasional'));
        $this->assertSame(1, GalleryAlbum::published()->count());
    }
}
