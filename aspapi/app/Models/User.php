<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified',
        'email_verified_at',
        'email_verify_token',
        'region_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verified'    => 'boolean',
            'password'          => 'hashed',
        ];
    }

    // ── Relations ──────────────────────────────────────────────────────────────

    public function member()
    {
        return $this->hasOne(\App\Models\Member::class);
    }

    public function region()
    {
        return $this->belongsTo(\App\Models\Region::class);
    }
}