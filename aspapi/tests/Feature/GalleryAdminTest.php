<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryAdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_guest_and_non_admin_cannot_access(): void
    {
        $this->get(route('admin.gallery.index'))->assertRedirect(route('login'));

        $member = User::factory()->create(['role' => 'anggota']);
        $this->actingAs($member)->get(route('admin.gallery.index'))->assertForbidden();
    }

    public function test_admin_can_create_album(): void
    {
        $region = Region::create(['name' => 'ASPAPI Jawa Barat', 'province' => 'Jawa Barat']);

        $response = $this->actingAs($this->admin())->post(route('admin.gallery.store'), [
            'title'          => 'Rapat Kerja Nasional 2026',
            'activity_type'  => '',
            'event_date'     => '2026-02-16',
            'event_date_end' => '2026-02-18',
            'place'          => 'Bandung',
            'region_id'      => $region->id,
            'is_published'   => '1',
        ]);

        $album = GalleryAlbum::firstOrFail();
        $response->assertRedirect(route('admin.gallery.photos.index', $album));

        $this->assertSame('rapat-kerja-nasional-2026', $album->slug);
        $this->assertSame(GalleryAlbum::DEFAULT_ACTIVITY_TYPE, $album->activity_type);
        $this->assertTrue($album->is_published);
        $this->assertSame($region->id, $album->region_id);
    }

    public function test_validation_rejects_end_date_before_start(): void
    {
        $this->actingAs($this->admin())->post(route('admin.gallery.store'), [
            'title'          => 'X',
            'event_date'     => '2026-02-16',
            'event_date_end' => '2026-02-10',
        ])->assertSessionHasErrors('event_date_end');

        $this->assertDatabaseCount('gallery_albums', 0);
    }

    public function test_admin_index_create_edit_pages_render_and_filter(): void
    {
        $admin = $this->admin();
        $a = GalleryAlbum::create(['title' => 'Kunjungan 2025', 'slug' => 'k25', 'event_date' => '2025-05-01']);
        GalleryAlbum::create(['title' => 'Seminar 2026', 'slug' => 's26', 'event_date' => '2026-05-01']);

        $this->actingAs($admin)->get(route('admin.gallery.index'))
            ->assertOk()->assertSee('Kunjungan 2025')->assertSee('Seminar 2026');

        $this->actingAs($admin)->get(route('admin.gallery.index', ['year' => 2025]))
            ->assertOk()->assertSee('Kunjungan 2025')->assertDontSee('Seminar 2026');

        $this->actingAs($admin)->get(route('admin.gallery.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.gallery.edit', $a))->assertOk()->assertSee('Kunjungan 2025');
    }

    public function test_admin_can_update_album(): void
    {
        $album = GalleryAlbum::create(['title' => 'Lama', 'slug' => 'lama', 'event_date' => '2026-01-01']);

        $this->actingAs($this->admin())->put(route('admin.gallery.update', $album), [
            'title'      => 'Baru',
            'slug'       => 'lama',
            'event_date' => '2026-01-02',
        ])->assertRedirect(route('admin.gallery.index'));

        $album->refresh();
        $this->assertSame('Baru', $album->title);
        $this->assertSame('lama', $album->slug);
        $this->assertFalse($album->is_published);
    }

    public function test_deleting_album_removes_files(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/1/a.webp', 'x');
        Storage::disk('public')->put('gallery/1/thumbs/a.webp', 'x');

        $album = GalleryAlbum::create(['title' => 'Hapus', 'slug' => 'hapus', 'event_date' => '2026-01-01']);
        $album->photos()->create(['path' => 'gallery/1/a.webp', 'thumb_path' => 'gallery/1/thumbs/a.webp']);

        $this->actingAs($this->admin())->delete(route('admin.gallery.destroy', $album))
            ->assertRedirect(route('admin.gallery.index'));

        $this->assertDatabaseCount('gallery_albums', 0);
        $this->assertDatabaseCount('gallery_photos', 0);
        Storage::disk('public')->assertMissing(['gallery/1/a.webp', 'gallery/1/thumbs/a.webp']);
    }
}
