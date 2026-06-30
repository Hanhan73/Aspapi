<?php

namespace App\Services;

use App\Models\Receipt;
use App\Models\ReceiptCounter;
use Illuminate\Support\Facades\DB;

class ReceiptNumberGenerator
{
    /**
     * Generate kwitansi baru dengan nomor urut yang aman dari race condition.
     * Format: {urut3digit}/ASPAPI/BD/{bulan2digit}/{tahun4digit}
     * Urut reset ke 1 setiap pergantian tahun (berdasarkan tahun saat kwitansi diterbitkan).
     */
    public static function issue(array $data): Receipt
    {
        $now  = now();
        $year = $now->year;

        return DB::transaction(function () use ($data, $now, $year) {
            $counter = ReceiptCounter::firstOrCreate(
                ['year' => $year],
                ['last_sequence' => 0]
            );

            // Kunci baris counter tahun ini supaya verifikasi yang nyaris bersamaan
            // tetap antre dapat nomor urut berbeda.
            $counter = ReceiptCounter::where('year', $year)->lockForUpdate()->first();

            $nextSequence = $counter->last_sequence + 1;
            $counter->update(['last_sequence' => $nextSequence]);

            $receiptNumber = sprintf(
                '%03d/ASPAPI/BD/%02d/%d',
                $nextSequence,
                $now->month,
                $year
            );

            return Receipt::create(array_merge($data, [
                'sequence'       => $nextSequence,
                'year'           => $year,
                'receipt_number' => $receiptNumber,
            ]));
        });
    }
}
