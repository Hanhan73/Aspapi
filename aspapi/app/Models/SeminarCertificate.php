<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeminarCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id', 'certificate_number', 'score', 'issued_at', 'file_path',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'score'     => 'integer',
    ];

    // ── Relations ──────────────────────────────────────────────────────────────

    public function enrollment()
    {
        return $this->belongsTo(SeminarEnrollment::class, 'enrollment_id');
    }

    // ── Static Helpers ─────────────────────────────────────────────────────────

    /**
     * Generate nomor sertifikat unik: CERT/ASPAPI/2026/0001
     */
public static function generateNumber(): string
{
    $now   = now();
    $year  = $now->year;
    $month = $now->month;

    $roman = [
        1  => 'I',   2  => 'II',   3  => 'III',
        4  => 'IV',  5  => 'V',    6  => 'VI',
        7  => 'VII', 8  => 'VIII', 9  => 'IX',
        10 => 'X',   11 => 'XI',   12 => 'XII',
    ];

    $count = static::whereYear('issued_at', $year)
        ->whereMonth('issued_at', $month)
        ->count();

    $sequence = str_pad($count + 1, 2, '0', STR_PAD_LEFT);

    return "{$sequence}/ASPAPI/SF/{$roman[$month]}/{$year}";
}
}