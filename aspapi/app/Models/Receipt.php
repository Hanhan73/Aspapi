<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'receipt_number',
        'sequence',
        'year',
        'source_type',
        'source_id',
        'member_id',
        'region_id',
        'payment_id_list',
        'amount',
        'payer_name',
        'purpose',
        'issued_by',
    ];

    protected $casts = [
        'payment_id_list' => 'array',
        'amount'          => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function amountLabel(): string
    {
        return 'Rp ' . number_format((float) $this->amount, 0, ',', '.');
    }

    public function amountInWords(): string
    {
        return ucfirst(\App\Helpers\Terbilang::make((int) $this->amount)) . ' rupiah';
    }
}
