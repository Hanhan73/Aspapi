<?php
// app/Services/ImageStore.php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * Simpan gambar upload sebagai WebP yang sudah di-resize (+ thumbnail opsional).
 * Reusable: galeri, thumbnail berita, logo, dll.
 *
 *   $stored = app(ImageStore::class)->storeWebp($file, 'gallery/12');
 *   // ['path' => 'gallery/12/abc.webp', 'width' => 1600, 'height' => 1067,
 *   //  'thumb_path' => 'gallery/12/thumbs/abc.webp', 'thumb_width' => 600, 'thumb_height' => 400]
 */
class ImageStore
{
    private ImageManager $manager;

    public function __construct(?ImageManager $manager = null)
    {
        // Driver GD; orientasi EXIF (foto HP) otomatis dikoreksi oleh Intervention v3.
        $this->manager = $manager ?? new ImageManager(new Driver());
    }

    /**
     * @param  UploadedFile|string  $source     file upload atau path absolut
     * @param  string               $directory  folder tujuan di disk (tanpa slash di akhir)
     * @param  int                  $maxSize    sisi terpanjang gambar utama (px), tidak di-upscale
     * @param  int|null             $thumbWidth lebar thumbnail (px); null = tanpa thumbnail
     */
    public function storeWebp(
        UploadedFile|string $source,
        string $directory,
        int $maxSize = 1600,
        ?int $thumbWidth = 600,
        int $quality = 82,
        string $disk = 'public',
    ): array {
        $this->ensureMemory();

        $sourcePath = $source instanceof UploadedFile ? $source->getRealPath() : $source;
        $directory  = trim($directory, '/');
        $name       = Str::random(32) . '.webp';
        $storage    = Storage::disk($disk);

        $main = $this->manager->read($sourcePath)->scaleDown(width: $maxSize, height: $maxSize);
        $result = $this->put($storage, "{$directory}/{$name}", $main, $quality);

        if ($thumbWidth) {
            // Baca ulang dari gambar utama yang sudah kecil — lebih hemat memori.
            $thumb = $this->manager->read($result['binary'])->scaleDown(width: $thumbWidth);
            $t = $this->put($storage, "{$directory}/thumbs/{$name}", $thumb, $quality - 4);

            unset($t['binary']);
            $result += [
                'thumb_path'   => $t['path'],
                'thumb_width'  => $t['width'],
                'thumb_height' => $t['height'],
            ];
        }

        unset($result['binary']);

        return $result;
    }

    /** Hapus beberapa file sekaligus (null diabaikan). */
    public function delete(array $paths, string $disk = 'public'): void
    {
        $paths = array_values(array_filter($paths));
        if ($paths) {
            Storage::disk($disk)->delete($paths);
        }
    }

    private function put($storage, string $path, ImageInterface $image, int $quality): array
    {
        $binary = (string) $image->toWebp(quality: $quality);
        $storage->put($path, $binary);

        return [
            'path'   => $path,
            'width'  => $image->width(),
            'height' => $image->height(),
            'binary' => $binary,
        ];
    }

    /** Foto kamera 24 MP butuh ±150 MB di GD; naikkan limit bila terlalu kecil. */
    private function ensureMemory(): void
    {
        $limit = ini_get('memory_limit');
        if ($limit === '-1') {
            return;
        }

        $bytes = (int) $limit * match (strtolower(substr($limit, -1))) {
            'g' => 1024 ** 3, 'm' => 1024 ** 2, 'k' => 1024, default => 1,
        };

        if ($bytes < 512 * 1024 ** 2) {
            @ini_set('memory_limit', '512M');
        }
    }
}
