<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeminarAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id', 'type', 'score', 'is_passed',
        'started_at', 'submitted_at',
    ];

    protected $casts = [
        'score'        => 'integer',
        'is_passed'    => 'boolean',
        'started_at'   => 'datetime',
        'submitted_at' => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────────────────────────

    public function enrollment()
    {
        return $this->belongsTo(SeminarEnrollment::class, 'enrollment_id');
    }

    public function answers()
    {
        return $this->hasMany(SeminarAttemptAnswer::class, 'attempt_id');
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Hitung dan simpan skor berdasarkan jawaban yang sudah diisi.
     * Panggil ini setelah semua SeminarAttemptAnswer tersimpan.
     */
    public function calculateAndSaveScore(): void
    {
        $total   = $this->answers()->count();
        $correct = $this->answers()->where('is_correct', true)->count();

        $this->score        = $total > 0 ? (int) round(($correct / $total) * 100) : 0;
        $this->submitted_at = now();

        // Cek passing grade dari seminar
        $passingGrade   = $this->enrollment->seminar->passing_grade;
        $this->is_passed = $this->score >= $passingGrade;

        $this->save();
    }
}