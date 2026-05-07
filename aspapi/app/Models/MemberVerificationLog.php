<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberVerificationLog extends Model
{
    protected $fillable = [
        'member_id',
        'verified_by',
        'action',
        'note',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'approve_biodata'    => 'Biodata Disetujui',
            'reject_biodata'     => 'Biodata Ditolak',
            'approve_old_member' => 'Klaim Anggota Lama Dikonfirmasi',
            'reject_old_member'  => 'Klaim Anggota Lama Ditolak',
            'approve_payment'    => 'Pembayaran Diverifikasi',
            'reject_payment'     => 'Pembayaran Ditolak',
            default              => $this->action,
        };
    }
}