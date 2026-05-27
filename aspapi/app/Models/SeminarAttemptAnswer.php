<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeminarAttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id', 'question_id', 'answer', 'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // ── Relations ──────────────────────────────────────────────────────────────

    public function attempt()
    {
        return $this->belongsTo(SeminarAttempt::class, 'attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(SeminarQuestion::class, 'question_id');
    }
}