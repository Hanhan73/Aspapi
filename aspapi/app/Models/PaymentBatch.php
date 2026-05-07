<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id', 'submitted_by', 'receipt_path',
        'total_amount', 'member_count', 'status',
        'reject_reason', 'verified_by', 'verified_at', 'payment_year',
    ];

    protected $casts = [
        'verified_at'  => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function region()    { return $this->belongsTo(Region::class); }
    public function submitter() { return $this->belongsTo(User::class, 'submitted_by'); }
    public function verifier()  { return $this->belongsTo(User::class, 'verified_by'); }
    public function payments()  { return $this->hasMany(Payment::class, 'batch_id'); }
}