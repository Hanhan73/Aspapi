<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryAlbum extends Model
{
    use HasFactory;

    public const DEFAULT_ACTIVITY_TYPE = 'Kegiatan ASPAPI';

    protected $fillable = [
        'title', 'slug', 'activity_type',
        'event_date', 'event_date_end', 'place',
        'region_id', 'agenda_id', 'seminar_id',
        'description', 'cover_photo_id',
        'is_published', 'sort_order',
    ];

    protected $casts = [
        'event_date'     => 'date',
        'event_date_end' => 'date',
        'is_published'   => 'boolean',
        'sort_order'     => 'integer',
    ];

    protected $attributes = [
        'activity_type' => self::DEFAULT_ACTIVITY_TYPE,
        'is_published'  => false,
        'sort_order'    => 0,
    ];

    /** Contoh jenis kegiatan untuk datalist di form admin. */
    public static function activityTypes(): array
    {
        return [self::DEFAULT_ACTIVITY_TYPE, 'Seminar', 'Rapat Kerja', 'Kunjungan', 'Pelatihan'];
    }

    // ── Relations ─────────────────────────────────────────────────────────────

    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class)->orderBy('sort_order')->orderBy('id');
    }

    public function cover()
    {
        return $this->belongsTo(GalleryPhoto::class, 'cover_photo_id');
    }

    /** Foto urutan pertama — fallback sampul, aman di-eager-load (with('firstPhoto')). */
    public function firstPhoto()
    {
        return $this->hasOne(GalleryPhoto::class)->ofMany(['sort_order' => 'min', 'id' => 'min']);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeLatestEvent($query)
    {
        return $query->orderByDesc('event_date')->orderBy('sort_order')->orderByDesc('id');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Foto sampul: cover terpilih, atau foto pertama sebagai fallback. */
    public function coverPhoto(): ?GalleryPhoto
    {
        if ($this->cover_photo_id && $this->cover) {
            return $this->cover;
        }

        if ($this->relationLoaded('photos')) {
            return $this->photos->first();
        }

        return $this->firstPhoto;
    }

    public function coverThumbUrl(): ?string
    {
        $photo = $this->coverPhoto();
        return $photo ? Storage::url($photo->thumb_path) : null;
    }

    public function coverUrl(): ?string
    {
        $photo = $this->coverPhoto();
        return $photo ? Storage::url($photo->path) : null;
    }

    /**
     * Tanggal format Indonesia, mendukung rentang:
     *  16 Februari 2026 | 16–18 Februari 2026 | 30 Januari – 2 Februari 2026 | 30 Desember 2025 – 2 Januari 2026
     */
    public function dateLabel(): string
    {
        $start = $this->event_date;
        $end   = $this->event_date_end;

        if (! $start) {
            return '';
        }

        $fmt = fn (CarbonInterface $d, string $pattern) => $d->copy()->locale('id')->translatedFormat($pattern);

        if (! $end || $end->isSameDay($start) || $end->lt($start)) {
            return $fmt($start, 'j F Y');
        }

        if ($start->isSameMonth($end, true)) {
            return $start->day . '–' . $fmt($end, 'j F Y');
        }

        if ($start->isSameYear($end)) {
            return $fmt($start, 'j F') . ' – ' . $fmt($end, 'j F Y');
        }

        return $fmt($start, 'j F Y') . ' – ' . $fmt($end, 'j F Y');
    }

    /** Agenda/Seminar terkait (untuk caption). */
    public function relatedLabel(): ?string
    {
        if ($this->agenda_id && $this->agenda) {
            return 'Agenda: ' . $this->agenda->title;
        }
        if ($this->seminar_id && $this->seminar) {
            return 'Seminar: ' . $this->seminar->title;
        }
        return null;
    }

    /**
     * Baris caption (teks mentah, belum di-escape — escape dilakukan di Blade / JS textContent).
     * Dipakai oleh komponen <x-gallery-caption> dan data caption lightbox.
     */
    public function captionLines(?GalleryPhoto $photo = null): array
    {
        $line1 = collect([$this->dateLabel(), $this->activity_type])->filter()->implode(' · ');

        $line2 = collect([
            $this->place ? 'Tempat: ' . $this->place : null,
            $this->region ? 'Daerah: ' . $this->region->name : null,
            $this->relatedLabel(),
        ])->filter()->implode(' · ');

        $line3 = $photo?->note ? trim($photo->note) : null;

        return array_values(array_filter([$line1, $line2, $line3], fn ($l) => filled($l)));
    }

    /** Ringkasan untuk meta description. */
    public function metaDescription(): string
    {
        $base = $this->description
            ?: implode('. ', $this->captionLines());

        return Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($base))), 160);
    }

    /** Slug unik dari judul. */
    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'album';
        $slug = $base;
        $i    = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    protected static function booted(): void
    {
        // Hapus file fisik sebelum baris foto ikut terhapus (cascade DB tidak memicu event model).
        static::deleting(function (GalleryAlbum $album) {
            $album->photos()->get()->each->deleteFiles();
        });
    }
}
