<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'file_path', 'file_name',
        'file_type', 'file_size', 'category',
        'is_public', 'sort_order', 'downloads',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) return '-';
        $kb = $this->file_size / 1024;
        if ($kb < 1024) return round($kb, 1) . ' KB';
        return round($kb / 1024, 1) . ' MB';
    }
}
