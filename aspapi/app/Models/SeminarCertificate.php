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
        $year  = now()->year;
        $count = static::whereYear('issued_at', $year)->count() + 1;

        return 'CERT/ASPAPI/' . $year . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}