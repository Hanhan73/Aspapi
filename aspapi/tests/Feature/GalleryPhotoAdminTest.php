<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryPhotoAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private GalleryAlbum $album;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->album = GalleryAlbum::create(['title' => 'Rakernas', 'slug' => 'rakernas', 'event_date' => '2026-02-16']);
    }

    private function upload(UploadedFile $file)
    {
        return $this->actingAs($this->admin)
            ->postJson(route('admin.gallery.photos.store', $this->album), ['photo' => $file]);
    }

    public function test_photos_page_renders(): void
    {
        $this->actingAs($this->admin)->get(route('admin.gallery.photos.index', $this->album))
            ->assertOk()->assertSee('Preview Caption')->assertSee('16 Februari 2026 · Kegiatan ASPAPI');
    }

    public function test_admin_can_upload_photo_resized_to_webp_with_thumbnail(): void
    {
        $response = $this->upload(UploadedFile::fake()->image('besar.jpg', 3000, 2000))
            ->assertCreated()
            ->assertJsonPath('photo.width', 1600)
            ->assertJsonPath('photo.height', 1067)
            ->assertJsonPath('photo.thumb_width', 600)
            ->assertJsonPath('photo.thumb_height', 400);

        $photo = GalleryPhoto::firstOrFail();
        $this->assertStringEndsWith('.webp', $photo->path);
        $this->assertStringStartsWith("gallery/{$this->album->id}/thumbs/", $photo->thumb_path);
        Storage::disk('public')->assertExists([$photo->path, $photo->thumb_path]);

        $this->assertSame('image/webp', getimagesizefromstring(Storage::disk('public')->get($photo->path))['mime']);
        $this->assertSame($photo->id, $response->json('photo.id'));
    }

    public function test_small_image_is_not_upscaled_and_png_webp_accepted(): void
    {
        $this->upload(UploadedFile::fake()->image('kecil.png', 400, 300))
            ->assertCreated()
            ->assertJsonPath('photo.width', 400)
            ->assertJsonPath('photo.thumb_width', 400);

        $this->upload(UploadedFile::fake()->image('foto.webp', 800, 600))->assertCreated();

        $this->assertSame([1, 2], GalleryPhoto::orderBy('id')->pluck('sort_order')->all());
    }

    public function test_upload_rejects_wrong_type_and_oversize(): void
    {
        $this->upload(UploadedFile::fake()->create('dok.pdf', 100, 'application/pdf'))
            ->assertUnprocessable()->assertJsonValidationErrors('photo');

        $this->upload(UploadedFile::fake()->image('raksasa.jpg')->size(9000))
            ->assertUnprocessable()->assertJsonValidationErrors('photo');

        $this->assertDatabaseCount('gallery_photos', 0);
    }

    public function test_note_cover_reorder_and_delete(): void
    {
        $a = $this->album->photos()->create(['path' => 'a.webp', 'thumb_path' => 'ta.webp', 'sort_order' => 0]);
        $b = $this->album->photos()->create(['path' => 'b.webp', 'thumb_path' => 'tb.webp', 'sort_order' => 1]);
        Storage::disk('public')->put('b.webp', 'x');
        Storage::disk('public')->put('tb.webp', 'x');

        $this->actingAs($this->admin)
            ->patchJson(route('admin.gallery.photos.update', [$this->album, $a]), ['note' => '  Sambutan ketua  '])
            ->assertOk()->assertJsonPath('photo.note', 'Sambutan ketua');

        $this->actingAs($this->admin)
            ->postJson(route('admin.gallery.photos.cover', [$this->album, $b]))
            ->assertOk()->assertJsonPath('cover_photo_id', $b->id);

        $this->actingAs($this->admin)
            ->postJson(route('admin.gallery.photos.reorder', $this->album), ['ids' => [$b->id, $a->id]])
            ->assertOk();
        $this->assertSame([$b->id, $a->id], $this->album->photos()->pluck('id')->all());

        $this->actingAs($this->admin)
            ->deleteJson(route('admin.gallery.photos.destroy', [$this->album, $b]))
            ->assertOk()->assertJsonPath('cover_photo_id', null);

        Storage::disk('public')->assertMissing(['b.webp', 'tb.webp']);
        $this->assertModelMissing($b);
    }

    public function test_photo_of_another_album_is_not_reachable(): void
    {
        $other = GalleryAlbum::create(['title' => 'Lain', 'slug' => 'lain', 'event_date' => '2026-01-01']);
        $foreign = $other->photos()->create(['path' => 'x.webp', 'thumb_path' => 'tx.webp']);

        $this->actingAs($this->admin)
            ->deleteJson(route('admin.gallery.photos.destroy', [$this->album, $foreign]))
            ->assertNotFound();

        $this->actingAs($this->admin)
            ->postJson(route('admin.gallery.photos.reorder', $this->album), ['ids' => [$foreign->id]])
            ->assertOk();
        $this->assertSame(0, $foreign->fresh()->sort_order);
        $this->assertModelExists($foreign);
    }

    public function test_non_admin_cannot_upload(): void
    {
        $member = User::factory()->create(['role' => 'anggota']);

        $this->actingAs($member)
            ->postJson(route('admin.gallery.photos.store', $this->album), ['photo' => UploadedFile::fake()->image('a.jpg')])
            ->assertForbidden();
    }
}
