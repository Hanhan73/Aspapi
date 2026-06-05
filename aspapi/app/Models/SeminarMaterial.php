<?php
// app/Models/SeminarMaterial.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeminarMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['seminar_id', 'label', 'url', 'sort_order'];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    /**
     * Ekstrak file ID dari URL Google Drive untuk embed.
     * Mendukung format /d/ID/view dan /open?id=ID
     */
    public function getEmbedUrlAttribute(): string
    {
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $this->url, $m)) {
            return "https://drive.google.com/file/d/{$m[1]}/preview";
        }
        if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $this->url, $m)) {
            return "https://drive.google.com/file/d/{$m[1]}/preview";
        }
        return $this->url;
    }
}