<?php
// app/Models/Agenda.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id', 'title', 'event_date',
        'description', 'photo', 'status', 'reject_reason',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}