<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advisor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'title', 'position', 'institution',
        'email', 'photo', 'bio', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
