<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seminar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'category', 'description', 'thumbnail',
        'passing_grade', 'is_active',
        // material_url dihapus — pakai relasi materials()
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'passing_grade' => 'integer',
    ];

    // ── Relations ──────────────────────────────────────────────────────────────

    public function questions()
    {
        return $this->hasMany(SeminarQuestion::class);
    }

    public function enrollments()
    {
        return $this->hasMany(SeminarEnrollment::class);
    }

    public function materials()
    {
        return $this->hasMany(SeminarMaterial::class)->orderBy('sort_order');
    }

    // ── Accessors ──────────────────────────────────────────────────────────────

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('images/seminar-default.png');
    }

    public function getQuestionCountAttribute(): int
    {
        return $this->questions()->count();
    }
}