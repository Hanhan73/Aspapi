<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'member_number',
        'full_name',
        'front_title',
        'back_title',
        'email',
        'phone',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'last_education',
        'member_type',
        'registration_type',
        'claims_old_member',
        'claimed_join_year',
        'biodata_status',
        'biodata_reject_reason',
        'institution',
        'occupation',
        'position',
        'province_id',
        'city_id',
        'province',
        'city',
        'address',
        'photo',
        'status',
        'registered_at',
        'active_until',
        'dues_paid',
        'dues_paid_at',
        'dues_receipt',
        'is_batch',
        'registered_by_region_id',
        'show_title_on_card',
    ];

    protected $casts = [
        'dues_paid'         => 'boolean',
        'claims_old_member' => 'boolean',
        'is_batch'          => 'boolean',
        'registered_at'     => 'date',
        'active_until'      => 'date',
        'dues_paid_at'      => 'date',
        'birth_date'        => 'date',
    ];

    // ── Relations ──────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provinceModel()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function cityModel()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function registeredByRegion()
    {
        return $this->belongsTo(Region::class, 'registered_by_region_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'registered_by_region_id');
    }

    // ── Accessors ──────────────────────────────────────────────────────────────

    public function getMemberTypeLabelAttribute(): string
    {
        return match ($this->member_type) {
            'biasa'      => 'Anggota Biasa',
            'luar_biasa' => 'Anggota Luar Biasa',
            'kehormatan' => 'Anggota Kehormatan',
            default      => $this->member_type ?? '-',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'Menunggu Verifikasi',
            'active'   => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'rejected' => 'Ditolak',
            default    => $this->status ?? '-',
        };
    }

    public function getBiodataStatusLabelAttribute(): string
    {
        return match ($this->biodata_status) {
            'pending'  => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default    => '-',
        };
    }

    public function getLastEducationLabelAttribute(): string
    {
        return match ($this->last_education) {
            'sd'       => 'SD',
            'smp'      => 'SMP',
            'sma'      => 'SMA/SMK',
            'd3'       => 'D3',
            's1'       => 'S1',
            's2'       => 'S2',
            's3'       => 'S3',
            'profesi'  => 'Profesi',
            'lainnya'  => 'Lainnya',
            default    => $this->last_education ?? '-',
        };
    }

    // ── Payment Helpers ────────────────────────────────────────────────────────

    public function hasPaidUangPangkal(): bool
    {
        return $this->payments()
            ->where('type', 'uang_pangkal')
            ->where('status', 'verified')
            ->exists();
    }

    /**
     * Cek apakah iuran tahunan masih aktif berdasarkan active_until.
     * Tidak lagi berbasis payment_year, melainkan tanggal kadaluarsa nyata.
     */
    public function hasPaidIuranTahunan(): bool
    {
        return $this->active_until !== null && $this->active_until->isFuture();
    }

    /**
     * Cek apakah masa aktif iuran akan segera berakhir dalam N hari ke depan.
     * Digunakan untuk menampilkan banner peringatan di halaman pembayaran.
     *
     * @param int $days Jumlah hari sebelum kadaluarsa untuk mulai memperingatkan (default 30)
     */
    public function isDuesExpiringSoon(int $days = 30): bool
    {
        if (! $this->active_until || ! $this->active_until->isFuture()) {
            return false;
        }

        return now()->diffInDays($this->active_until, false) <= $days;
    }

    /**
     * Cek apakah anggota sudah memenuhi syarat generate KTA.
     * - Biodata harus verified
     * - Anggota baru  : uang pangkal sudah diverifikasi bendahara
     * - Anggota lama  : iuran tahunan masih aktif (active_until di masa depan)
     */
    public function canGenerateCard(): bool
    {
        if ($this->biodata_status !== 'verified') {
            return false;
        }

        if ($this->registration_type === 'baru') {
            return $this->hasPaidUangPangkal();
        }

        return $this->hasPaidIuranTahunan();
    }

    // ── Nomor Anggota ──────────────────────────────────────────────────────────

    /**
     * Build nomor anggota 11 digit:
     * [2 Kode Prov][2 Kode Kota][1 Gender][2 Tahun][4 Urutan]
     */
    public static function buildMemberNumber(
        string $provinceCode,
        string $cityCode,
        string $gender,
        int    $year,
        int    $sequence
    ): string {
        $genderCode = $gender === 'L' ? '1' : '2';
        $yearCode   = substr((string) $year, -2);
        $seq        = str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return $provinceCode . $cityCode . $genderCode . $yearCode . $seq;
    }

    /**
     * Generate & simpan nomor anggota secara atomic (aman dari race condition).
     */
    public function assignMemberNumber(): void
    {
        if ($this->member_number) {
            return;
        }

        $province = $this->provinceModel;
        $city     = $this->cityModel;

        if (! $province || ! $city) {
            throw new \RuntimeException(
                "Tidak bisa generate nomor anggota: data provinsi/kota tidak lengkap untuk member ID {$this->id}."
            );
        }

        $year       = $this->claimed_join_year ?? now()->year;
        $genderCode = ($this->gender ?? 'L') === 'L' ? '1' : '2';
        $prefix     = $province->code . $city->code . $genderCode . substr((string) $year, -2);

        DB::transaction(function () use ($prefix, $province, $city, $year) {
            $lastNumber = static::where('member_number', 'like', $prefix . '%')
                ->lockForUpdate()
                ->orderBy('member_number', 'desc')
                ->value('member_number');

            $lastSequence = $lastNumber ? (int) substr($lastNumber, -4) : 0;
            $nextSequence = $lastSequence + 1;

            $this->member_number = static::buildMemberNumber(
                $province->code,
                $city->code,
                $this->gender ?? 'L',
                $year,
                $nextSequence
            );

            $this->save();
        });
    }

    public function isBiodataComplete(): bool
    {
        return filled($this->nik)
            && filled($this->full_name)
            && filled($this->birth_place)
            && filled($this->birth_date)
            && filled($this->gender)
            && filled($this->last_education)
            && filled($this->address)
            && filled($this->phone)
            && filled($this->province_id)
            && filled($this->city_id);
    }

    public function getFullNameWithTitleAttribute(): string
    {
        $name      = $this->full_name ?? '';
        $frontTitle = trim($this->front_title ?? '');
        $backTitle  = trim($this->back_title ?? '');

        // Nama di-uppercase, gelar tetap as-is sesuai input
        $parts = [];

        if ($frontTitle !== '') {
            $parts[] = $frontTitle;
        }

        if ($backTitle !== '') {
            $parts[] = strtoupper($name) . ', ' . $backTitle;
        } else {
            $parts[] = strtoupper($name);
        }

        return implode(' ', $parts);
    }

public function getDisplayNameAttribute(): string
{
    if (!$this->show_title_on_card) {
        return strtoupper($this->full_name ?? '');
    }
    return $this->full_name_with_title;
}

public function getCardNameLinesAttribute(): array
{
    $fullName   = strtoupper($this->full_name ?? '');
    $frontTitle = ($this->show_title_on_card && trim($this->front_title ?? '') !== '')
        ? trim($this->front_title) . ' '
        : '';
    $backTitle  = ($this->show_title_on_card && trim($this->back_title ?? '') !== '')
        ? ', ' . trim($this->back_title)
        : '';

    $words = explode(' ', $fullName);
    $count = count($words);

    // Selalu coba 1 baris dulu — pakai estimasi lebar karakter
    $widthOf = function (string $text): float {
        $w = 0;
        foreach (mb_str_split($text) as $char) {
            $up = strtoupper($char);
            if (in_array($char, [' ', '.', ',', ';', ':', '!', '|'])) {
                $w += 3;
            } elseif ($up === 'I') {
                $w += 2.5;
            } elseif (in_array($up, ['M', 'W'])) {
                $w += 8;
            } elseif (in_array($up, ['N', 'D', 'O', 'Q', 'U', 'G', 'C', 'H', 'K', 'R', 'A', 'B'])) {
                $w += 6.5;
            } else {
                $w += 5.5;
            }
        }
        return $w;
    };

    $maxWidth = 180; // unit lebar area nama (kiri QR → kiri foto)

    $oneLine = $frontTitle . $fullName . $backTitle;

    // Muat 1 baris → langsung return
    if ($widthOf($oneLine) <= $maxWidth) {
        return [$oneLine];
    }

    // Tidak muat → perlu 2 atau 3 baris
    // Cari split terbaik: coba tiap titik potong kata
    // Prioritas: kedua baris ≤ maxWidth, dan baris 1 tidak jauh lebih panjang dari baris 2

    if ($count === 1) {
        // Nama 1 kata tapi gelar panjang → nama baris 1, gelar baris 2
        return array_values(array_filter([
            $frontTitle . $fullName,
            trim($backTitle, ', ') ?: null,
        ]));
    }

    // Coba split 2 baris dulu
    $bestSplit2 = null;
    $bestDiff2  = PHP_INT_MAX;

    for ($i = 1; $i < $count; $i++) {
        $l1 = $frontTitle . implode(' ', array_slice($words, 0, $i));
        $l2 = implode(' ', array_slice($words, $i)) . $backTitle;
        $w1 = $widthOf($l1);
        $w2 = $widthOf($l2);

        if ($w1 <= $maxWidth && $w2 <= $maxWidth) {
            $diff = abs($w1 - $w2);
            if ($diff < $bestDiff2) {
                $bestDiff2  = $diff;
                $bestSplit2 = $i;
            }
        }
    }

    if ($bestSplit2 !== null) {
        return [
            $frontTitle . implode(' ', array_slice($words, 0, $bestSplit2)),
            implode(' ', array_slice($words, $bestSplit2)) . $backTitle,
        ];
    }

    // Tidak ada split 2 baris yang muat → coba 3 baris
    // Split: baris1 = gelar depan + kata1..i, baris2 = kata i+1..j, baris3 = sisa + gelar belakang
    for ($i = 1; $i < $count - 1; $i++) {
        for ($j = $i + 1; $j < $count; $j++) {
            $l1 = $frontTitle . implode(' ', array_slice($words, 0, $i));
            $l2 = implode(' ', array_slice($words, $i, $j - $i));
            $l3 = implode(' ', array_slice($words, $j)) . $backTitle;

            if ($widthOf($l1) <= $maxWidth && $widthOf($l2) <= $maxWidth && $widthOf($l3) <= $maxWidth) {
                return [$l1, $l2, $l3];
            }
        }
    }

    // Fallback ekstrem: paksa split per 2 kata, max 3 baris
    $line1 = $frontTitle . implode(' ', array_slice($words, 0, 2));
    $line2 = $count > 2 ? implode(' ', array_slice($words, 2, 2)) : null;

    if ($count > 4) {
        $line3 = implode(' ', array_slice($words, 4)) . $backTitle;
    } elseif ($line2) {
        $line2 .= $backTitle;
        $line3 = null;
    } else {
        $line3 = (trim($backTitle, ', ') !== '') ? trim($backTitle, ', ') : null;
    }

    return array_values(array_filter([$line1, $line2, $line3]));
    }
}