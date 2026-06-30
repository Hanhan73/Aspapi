<?php

namespace App\Services;

use App\Models\Receipt;
use App\Models\ReceiptCounter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReceiptNumberGenerator
{
    /**
     * Generate kwitansi baru dengan nomor urut yang aman dari race condition.
     * Format: {urut3digit}/ASPAPI/BD/{bulan2digit}/{tahun4digit}
     * Urut reset ke 1 setiap pergantian tahun.
     *
     * $issuedAt: tanggal "resmi" kwitansi ini diterbitkan. Default sekarang,
     * tapi untuk backfill data lama dipaksa pakai tanggal verifikasi aslinya
     * supaya nomor urut & tahunnya tetap kronologis & akurat.
     */
    public static function issue(array $data, ?Carbon $issuedAt = null): Receipt
    {
        $issuedAt = $issuedAt ?? now();
        $year     = $issuedAt->year;

        return DB::transaction(function () use ($data, $issuedAt, $year) {
            ReceiptCounter::firstOrCreate(
                ['year' => $year],
                ['last_sequence' => 0]
            );

            $counter = ReceiptCounter::where('year', $year)->lockForUpdate()->first();

            $nextSequence = $counter->last_sequence + 1;
            $counter->update(['last_sequence' => $nextSequence]);

            $receiptNumber = sprintf(
                '%03d/ASPAPI/BD/%02d/%d',
                $nextSequence,
                $issuedAt->month,
                $year
            );

            $receipt = Receipt::create(array_merge($data, [
                'sequence'       => $nextSequence,
                'year'           => $year,
                'receipt_number' => $receiptNumber,
            ]));

            // Supaya tanggal yang tercetak di kwitansi (created_at) ikut tanggal asli,
            // bukan tanggal command backfill dijalankan.
            if ($issuedAt->ne(now())) {
                $receipt->forceFill([
                    'created_at' => $issuedAt,
                    'updated_at' => $issuedAt,
                ])->saveQuietly();
            }

            return $receipt;
        });
    }
}
