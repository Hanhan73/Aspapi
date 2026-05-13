<?php
// app/Models/Partner.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'logo',
        'profile',
        'website_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Label kategori yang readable
     */
    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'perguruan_tinggi' => 'Perguruan Tinggi',
            'sekolah'          => 'Sekolah',
            'industri'         => 'Industri',
            'pemerintahan'     => 'Pemerintahan',
            default            => ucfirst($this->category),
        };
    }

    /**
     * Warna badge per kategori
     */
    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'perguruan_tinggi' => 'badge-primary',
            'sekolah'          => 'badge-info',
            'industri'         => 'badge-warning',
            'pemerintahan'     => 'badge-success',
            default            => 'badge-neutral',
        };
    }

    /**
     * Daftar kategori untuk select/filter
     */
    public static function categories(): array
    {
        return [
            'perguruan_tinggi' => 'Perguruan Tinggi',
            'sekolah'          => 'Sekolah',
            'industri'         => 'Industri',
            'pemerintahan'     => 'Pemerintahan',
        ];
    }

    // Scope: hanya yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}