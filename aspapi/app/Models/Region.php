<?php
// app/Models/Region.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'province',
        'chairperson',              // kolom lama (biarkan, bisa dipakai untuk backward compat)
        'chairman_name',            // kolom baru — nama ketua dengan gelar
        'chairman_title',           // kolom baru — institusi / jabatan ketua
        'period_start', 'period_end',
        'website_url',
        'email', 'phone', 'address',
        'description',
        'photo', 'cover_image',
        'is_active', 'sort_order',
        'notification_email',       // email notifikasi khusus untuk admin daerah (bisa beda dari user login)
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke user akun daerah
    public function users()
    {
        return $this->hasMany(User::class, 'region_id');
    }

    public function activeUser()
    {
        return $this->hasOne(User::class, 'region_id')
                    ->where('role', 'aspapi_daerah');
    }

    // Anggota yang didaftarkan melalui daerah ini
    public function members()
    {
        return $this->hasMany(Member::class, 'registered_by_region_id');
    }

    // Accessor: periode format "2023–2027"
    public function getPeriodAttribute(): string
    {
        if ($this->period_start && $this->period_end) {
            return $this->period_start . '–' . $this->period_end;
        }
        return '—';
    }

    // Accessor: apakah periode masih aktif
    public function getPeriodIsActiveAttribute(): bool
    {
        return $this->period_end >= date('Y');
    }

    // Auto-generate slug dari province jika kosong
    protected static function booted(): void
    {
        static::creating(function ($region) {
            if (empty($region->slug)) {
                $region->slug = Str::slug($region->province);
            }
        });
    }
}