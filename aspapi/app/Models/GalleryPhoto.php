<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_album_id', 'path', 'thumb_path',
        'width', 'height', 'thumb_width', 'thumb_height',
        'note', 'sort_order',
    ];

    protected $casts = [
        'width'        => 'integer',
        'height'       => 'integer',
        'thumb_width'  => 'integer',
        'thumb_height' => 'integer',
        'sort_order'   => 'integer',
    ];

    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    public function getThumbUrlAttribute(): string
    {
        return Storage::url($this->thumb_path);
    }

    /** Hapus file foto & thumbnail dari disk public. */
    public function deleteFiles(): void
    {
        Storage::disk('public')->delete(array_filter([$this->path, $this->thumb_path]));
    }

    protected static function booted(): void
    {
        static::deleting(function (GalleryPhoto $photo) {
            $photo->deleteFiles();

            // Lepas cover jika foto ini adalah sampul album.
            GalleryAlbum::where('cover_photo_id', $photo->id)->update(['cover_photo_id' => null]);
        });
    }
}
