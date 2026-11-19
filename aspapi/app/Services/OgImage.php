<?php
// app/Services/OgImage.php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Throwable;

/**
 * Gambar preview untuk share link (Open Graph: WhatsApp, Facebook, Telegram, X).
 *
 * Crawler share sering gagal menampilkan WebP / gambar besar, dan butuh URL absolut
 * yang bisa diakses publik. Kelas ini membuat salinan JPG 1200×630 (di-cache di
 * storage/app/public/og) dan mengembalikan URL absolut berdasarkan domain request.
 *
 *   $ogImage = OgImage::url($news->thumbnail);   // null bila tidak ada gambar
 */
class OgImage
{
    public const WIDTH  = 1200;
    public const HEIGHT = 630;

    public static function url(?string $path, string $disk = 'public'): ?string
    {
        if (! $path) {
            return null;
        }

        $storage = Storage::disk($disk);

        try {
            if (! $storage->exists($path)) {
                return null;
            }

            // Nama cache ikut berubah bila file sumber diganti.
            $target = 'og/' . sha1($path . '|' . $storage->lastModified($path)) . '.jpg';

            if (! $storage->exists($target)) {
                $image = (new ImageManager(new Driver()))
                    ->read($storage->path($path))
                    ->cover(self::WIDTH, self::HEIGHT);

                $storage->put($target, (string) $image->toJpeg(quality: 80));
            }

            return asset('storage/' . $target);
        } catch (Throwable $e) {
            Log::warning('OG image: gagal membuat preview', ['path' => $path, 'error' => $e->getMessage()]);

            // Cadangan: pakai file asli dengan URL absolut.
            return asset('storage/' . ltrim($path, '/'));
        }
    }
}
