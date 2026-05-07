<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'dues_paid'          => 'boolean',
        'claims_old_member'  => 'boolean',
        'is_batch'           => 'boolean',
        'registered_at'      => 'date',
        'active_until'       => 'date',
        'dues_paid_at'       => 'date',
    ];

    public function user()           { return $this->belongsTo(User::class); }
    public function provinceModel()  { return $this->belongsTo(Province::class, 'province_id'); }
    public function cityModel()      { return $this->belongsTo(City::class, 'city_id'); }
    public function payments()       { return $this->hasMany(Payment::class); }
    public function registeredByRegion() { return $this->belongsTo(Region::class, 'registered_by_region_id'); }

    public function getMemberTypeLabelAttribute(): string
    {
        return match($this->member_type) {
            'biasa'      => 'Anggota Biasa',
            'luar_biasa' => 'Anggota Luar Biasa',
            'kehormatan' => 'Anggota Kehormatan',
            default      => $this->member_type,
        };
    }

    /**
     * Generate nomor anggota 11 digit:
     * [2 Kode Prov][2 Kode Kota][1 Gender][2 Tahun][4 Urutan]
     */
    public static function generateMemberNumber(
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
     * Auto-generate & assign nomor anggota
     */
    public function assignMemberNumber(): void
    {
        if ($this->member_number) return;

        $province = $this->provinceModel;
        $city     = $this->cityModel;

        if (!$province || !$city) return;

        $year     = $this->claimed_join_year ?? now()->year;
        $sequence = static::whereNotNull('member_number')->count() + 1;

        $this->member_number = static::generateMemberNumber(
            $province->code,
            $city->code,
            $this->gender ?? 'L',
            $year,
            $sequence
        );
        $this->save();
    }

    public function hasPaidUangPangkal(): bool
    {
        return $this->payments()
            ->where('type', 'uang_pangkal')
            ->where('status', 'verified')
            ->exists();
    }

    public function hasPaidIuranTahunan(int $year = null): bool
    {
        return $this->payments()
            ->where('type', 'iuran_tahunan')
            ->where('status', 'verified')
            ->where('payment_year', $year ?? now()->year)
            ->exists();
    }

    public function canGenerateCard(): bool
    {
        if ($this->biodata_status !== 'verified') return false;
        if ($this->registration_type === 'baru') {
            return $this->hasPaidUangPangkal();
        }
        return $this->hasPaidIuranTahunan();
    }
}