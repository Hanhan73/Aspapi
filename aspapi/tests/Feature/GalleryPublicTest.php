<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\GalleryAlbum;
use App\Models\Region;
use App\Models\Seminar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryPublicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function album(array $attrs = [], int $photos = 2): GalleryAlbum
    {
        $album = GalleryAlbum::create(array_merge([
            'title'        => 'Rapat Kerja Nasional',
            'slug'         => 'rakernas',
            'event_date'   => '2026-02-16',
            'is_published' => true,
        ], $attrs));

        for ($i = 1; $i <= $photos; $i++) {
            $album->photos()->create([
                'path' => "gallery/{$album->id}/p{$i}.webp", 'thumb_path' => "gallery/{$album->id}/thumbs/p{$i}.webp",
                'width' => 1600, 'height' => 1067, 'thumb_width' => 600, 'thumb_height' => 400,
                'sort_order' => $i,
            ]);
        }

        return $album;
    }

    public function test_draft_album_returns_404(): void
    {
        $this->album(['is_published' => false, 'slug' => 'draft']);

        $this->get(route('galleries.show', 'draft'))->assertNotFound();
        $this->get(route('galleries.index'))->assertOk()->assertDontSee('Rapat Kerja Nasional');
    }

    public function test_index_lists_published_albums_and_filters(): void
    {
        $region = Region::create(['name' => 'ASPAPI Jawa Barat', 'province' => 'Jawa Barat']);
        $this->album(['title' => 'Album Bandung', 'slug' => 'bdg', 'region_id' => $region->id]);
        $this->album(['title' => 'Album Lama', 'slug' => 'lama', 'event_date' => '2024-03-01']);
        $this->album(['title' => 'Album Kosong', 'slug' => 'kosong'], 0);

        $this->get(route('galleries.index'))->assertOk()
            ->assertSee('Album Bandung')->assertSee('Album Lama')->assertDontSee('Album Kosong')
            ->assertSee('2 foto');

        $this->get(route('galleries.index', ['tahun' => 2024]))->assertOk()
            ->assertSee('Album Lama')->assertDontSee('Album Bandung');

        $this->get(route('galleries.index', ['daerah' => $region->id]))->assertOk()
            ->assertSee('Album Bandung')->assertDontSee('Album Lama');

        $this->get(route('galleries.index', ['daerah' => 'pusat']))->assertOk()
            ->assertSee('Album Lama')->assertDontSee('Album Bandung');
    }

    public function test_show_renders_lightbox_markup_caption_and_seo(): void
    {
        $album = $this->album(['place' => 'Bandung', 'event_date_end' => '2026-02-18', 'description' => 'Rakernas tahunan ASPAPI.']);
        $album->photos()->first()->update(['note' => 'Sambutan <b>ketua</b>']);

        $html = $this->get(route('galleries.show', 'rakernas'))->assertOk()
            ->assertSee('data-pswp-gallery', false)
            ->assertSee('data-pswp-width="1600"', false)
            ->assertSee('width="600" height="400"', false)
            ->assertSee('loading="lazy"', false)
            ->assertSee('16–18 Februari 2026 · Kegiatan ASPAPI')
            ->assertSee('Tempat: Bandung')
            ->assertSee('Sambutan &lt;b&gt;ketua&lt;/b&gt;', false)
            ->assertSee('<meta name="description"', false)
            ->assertSee('Rakernas tahunan ASPAPI.')
            ->assertSee('/storage/gallery/' . $album->id . '/p1.webp', false)
            ->getContent();

        // File foto tidak ada di disk → preview share jatuh ke logo (URL absolut).
        $this->assertMatchesRegularExpression('#<meta property="og:image" content="http[^"]+/images/logo-aspapi\.png"#', $html);
        $this->assertStringNotContainsString('<b>ketua</b>', $html);
    }

    public function test_show_share_preview_uses_generated_jpeg(): void
    {
        Storage::fake('public');
        $album = $this->album();
        $source = UploadedFile::fake()->image('p1.webp', 1600, 1067);
        Storage::disk('public')->put("gallery/{$album->id}/p1.webp", $source->getContent());

        $html = $this->get(route('galleries.show', 'rakernas'))->assertOk()->getContent();

        $this->assertMatchesRegularExpression('#<meta property="og:image" content="http[^"]+/storage/og/[0-9a-f]{40}\.jpg"#', $html);
        $this->assertStringContainsString('<meta property="og:image:width" content="1200"', $html);
        $this->assertStringContainsString('<meta property="og:image:height" content="630"', $html);
        $this->assertStringContainsString('<meta name="twitter:image" content="http', $html);

        $files = Storage::disk('public')->files('og');
        $this->assertCount(1, $files);
        [$w, $h, $type] = getimagesizefromstring(Storage::disk('public')->get($files[0]));
        $this->assertSame([1200, 630, IMAGETYPE_JPEG], [$w, $h, $type]);
    }

    public function test_home_sitemap_agenda_seminar_integration(): void
    {
        $region  = Region::create(['name' => 'ASPAPI Jatim', 'province' => 'Jawa Timur']);
        $agenda  = Agenda::create(['region_id' => $region->id, 'title' => 'Musda Jatim', 'event_date' => '2026-03-01', 'status' => 'approved']);
        $seminar = Seminar::create(['title' => 'Seminar Kearsipan', 'passing_grade' => 70, 'is_active' => true]);

        $this->album(['title' => 'Dokumentasi Musda', 'slug' => 'musda', 'agenda_id' => $agenda->id]);
        $this->album(['title' => 'Dokumentasi Seminar', 'slug' => 'dok-seminar', 'seminar_id' => $seminar->id]);
        $this->album(['title' => 'Draft Rahasia', 'slug' => 'draft', 'is_published' => false, 'agenda_id' => $agenda->id]);

        $this->get(route('home'))->assertOk()->assertSee('Galeri Kegiatan')->assertSee('Dokumentasi Musda');

        $this->get(route('sitemap'))->assertOk()
            ->assertSee(route('galleries.index'), false)
            ->assertSee(route('galleries.show', 'musda'), false)
            ->assertDontSee(route('galleries.show', 'draft'), false);

        $this->get(route('public.agenda'))->assertOk()
            ->assertSee('agenda-docs-' . $agenda->id, false)
            ->assertSee(route('galleries.show', 'musda'), false)
            ->assertDontSee('Draft Rahasia');

        $this->get(route('public.seminars'))->assertOk()
            ->assertSee('Dokumentasi')
            ->assertSee(route('galleries.show', 'dok-seminar'), false);
    }

    public function test_navbar_has_gallery_link(): void
    {
        $this->get(route('galleries.index'))->assertOk()->assertSee(route('galleries.index'), false)->assertSee('Galeri');
    }
}
