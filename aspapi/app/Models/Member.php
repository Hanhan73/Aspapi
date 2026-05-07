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
        'user_id', 'member_number', 'full_name', 'email', 'phone', 'nik',
        'member_type', 'registration_type', 'claims_old_member', 'claimed_join_year',
        'biodata_status', 'biodata_reject_reason',
        'institution', 'study_program', 'position',
        'province_id', 'city_id', 'province', 'city', 'address',
        'gender', 'photo',
        'status', 'registered_at', 'active_until',
        'dues_paid', 'dues_paid_at', 'dues_receipt',
        'is_batch', 'registered_by_region_id',
    ];

    protected $casts = [
        'dues_paid'         => 'boolean',
        'claims_old_member' => 'boolean',
        'is_batch'          => 'boolean',
        'registered_at'     => 'date',
        'active_until'      => 'date',
        'dues_paid_at'      => 'date',
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

    // ── Payment Helpers ────────────────────────────────────────────────────────

    public function hasPaidUangPangkal(): bool
    {
        return $this->payments()
            ->where('type', 'uang_pangkal')
            ->where('status', 'verified')
            ->exists();
    }

    public function hasPaidIuranTahunan(?int $year = null): bool
    {
        return $this->payments()
            ->where('type', 'iuran_tahunan')
            ->where('status', 'verified')
            ->where('payment_year', $year ?? now()->year)
            ->exists();
    }

    /**
     * Cek apakah anggota sudah memenuhi syarat generate KTA.
     * - Biodata harus verified
     * - Anggota baru  : uang pangkal sudah diverifikasi bendahara
     * - Anggota lama  : iuran tahunan sudah diverifikasi bendahara
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
     *
     * Contoh: 3273 1 26 0001
     *         ↑↑↑↑ ↑ ↑↑ ↑↑↑↑
     *         prov kota gender tahun urut
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
     * Harus dipanggil di dalam DB::transaction() dari luar, atau method ini
     * akan membuat transaction sendiri.
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
            // Lock baris terakhir dengan prefix yang sama supaya tidak ada duplikat
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
            && filled($this->gender)
            && filled($this->address)
            && filled($this->phone)
            && filled($this->province_id)
            && filled($this->city_id);
    }
}