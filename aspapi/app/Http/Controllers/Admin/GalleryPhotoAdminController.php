<?php
// app/Http/Controllers/Admin/GalleryPhotoAdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Services\ImageStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class GalleryPhotoAdminController extends Controller
{
    public function index(GalleryAlbum $album)
    {
        $album->load(['photos', 'region', 'agenda', 'seminar']);

        $photos = $album->photos->map(fn (GalleryPhoto $p) => $this->payload($p))->values();

        return view('admin.gallery.photos', compact('album', 'photos'));
    }

    /** Satu file per request — klien mengunggah paralel (maks 3). */
    public function store(Request $request, GalleryAlbum $album, ImageStore $images)
    {
        $request->validate([
            'photo' => 'required|file|mimes:jpg,jpeg,png,webp|max:8192',
        ], [
            'photo.mimes' => 'Format harus JPG, PNG, atau WebP.',
            'photo.max'   => 'Ukuran maksimal 8 MB.',
        ]);

        try {
            $stored = $images->storeWebp($request->file('photo'), "gallery/{$album->id}");
        } catch (Throwable $e) {
            Log::warning('Galeri: gagal memproses gambar', ['album' => $album->id, 'error' => $e->getMessage()]);

            return response()->json(['message' => 'Gambar tidak dapat diproses. Pastikan file tidak rusak.'], 422);
        }

        $photo = $album->photos()->create($stored + [
            'sort_order' => (int) $album->photos()->max('sort_order') + 1,
        ]);

        return response()->json(['photo' => $this->payload($photo)], 201);
    }

    public function update(Request $request, GalleryAlbum $album, GalleryPhoto $photo)
    {
        $data = $request->validate(['note' => 'nullable|string|max:500']);

        $photo->update(['note' => filled($data['note'] ?? null) ? trim($data['note']) : null]);

        return response()->json(['photo' => $this->payload($photo)]);
    }

    public function cover(GalleryAlbum $album, GalleryPhoto $photo)
    {
        $album->update(['cover_photo_id' => $photo->id]);

        return response()->json(['cover_photo_id' => $photo->id]);
    }

    public function destroy(GalleryAlbum $album, GalleryPhoto $photo)
    {
        $photo->delete(); // file dihapus & cover dilepas di GalleryPhoto::booted()

        return response()->json(['cover_photo_id' => $album->fresh()->cover_photo_id]);
    }

    /** Sama dengan Mitra: terima urutan id, simpan index sebagai sort_order. */
    public function reorder(Request $request, GalleryAlbum $album)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);

        $owned = $album->photos()->pluck('id')->all();

        foreach (array_values($request->ids) as $order => $id) {
            if (in_array((int) $id, $owned, true)) {
                GalleryPhoto::where('id', $id)->update(['sort_order' => $order]);
            }
        }

        return response()->json(['success' => true]);
    }

    private function payload(GalleryPhoto $p): array
    {
        return [
            'id'           => $p->id,
            'url'          => $p->url,
            'thumb_url'    => $p->thumb_url,
            'width'        => $p->width,
            'height'       => $p->height,
            'thumb_width'  => $p->thumb_width,
            'thumb_height' => $p->thumb_height,
            'note'         => $p->note ?? '',
        ];
    }
}
