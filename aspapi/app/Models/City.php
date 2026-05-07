<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['province_id', 'code', 'name'];
    public function province() { return $this->belongsTo(Province::class); }
    public function members()  { return $this->hasMany(Member::class); }
}