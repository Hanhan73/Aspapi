<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class Payment extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'member_id', 'type', 'payment_method', 'amount',
        'receipt_path', 'status', 'reject_reason',
        'verified_by', 'verified_at', 'payment_year', 'batch_id',
        'notes', // ← tambahan untuk keterangan gabungan, batch, dll
    ];
 
    protected $casts = [
        'verified_at' => 'datetime',
        'amount'      => 'decimal:2',
    ];
 
    public function member()   { return $this->belongsTo(Member::class); }
    public function verifier() { return $this->belongsTo(User::class, 'verified_by'); }
    public function batch()    { return $this->belongsTo(PaymentBatch::class); }
 
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'uang_pangkal'  => 'Uang Pangkal',
            'iuran_tahunan' => 'Iuran Tahunan',
            'gabungan'      => 'Gabungan (Pangkal + Iuran)', // fallback jika ada data lama
            default         => $this->type,
        };
    }
 
    public function getAmountFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}