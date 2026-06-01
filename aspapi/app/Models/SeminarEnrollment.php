<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeminarEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'seminar_id', 'membership_period_start', 'status',
    ];

protected $casts = [
    'membership_period_start' => 'date',
    'member_id'               => 'integer',  // tambah ini
    'seminar_id'              => 'integer',  // tambah ini
];

    // ── Relations ──────────────────────────────────────────────────────────────

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function attempts()
    {
        return $this->hasMany(SeminarAttempt::class, 'enrollment_id');
    }

    public function certificate()
    {
        return $this->hasOne(SeminarCertificate::class, 'enrollment_id');
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    public function preTest(): ?SeminarAttempt
    {
        return $this->attempts()->where('type', 'pre_test')->latest()->first();
    }

    public function postTest(): ?SeminarAttempt
    {
        return $this->attempts()->where('type', 'post_test')->latest()->first();
    }

    public function isPreTestDone(): bool
    {
        return in_array($this->status, [
            'pre_test_done', 'material_read', 'post_test_done', 'completed',
        ]);
    }

    public function isMaterialRead(): bool
    {
        return in_array($this->status, ['material_read', 'post_test_done', 'completed']);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'enrolled'       => 'Terdaftar',
            'pre_test_done'  => 'Pre-Test Selesai',
            'material_read'  => 'Materi Dibaca',
            'post_test_done' => 'Post-Test Selesai',
            'completed'      => 'Lulus',
            default          => '-',
        };
    }
}