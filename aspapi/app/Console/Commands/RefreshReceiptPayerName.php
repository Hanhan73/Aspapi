<?php

namespace App\Console\Commands;

use App\Models\Receipt;
use Illuminate\Console\Command;

class RefreshReceiptPayerName extends Command
{
    protected $signature = 'kwitansi:refresh-payer-name {--dry-run : Cuma tampilkan perubahan, tanpa simpan}';

    protected $description = 'Update kolom payer_name di kwitansi yang sudah ada supaya pakai nama lengkap + gelar, tanpa ubah nomor/tanggal terbit';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        // Hanya kwitansi pembayaran mandiri yang punya member_id.
        // Kwitansi batch kolektif (payment_batch) payer_name-nya emang nama wilayah, bukan nama orang, jadi dilewati.
        $receipts = Receipt::where('source_type', 'payment')
            ->whereNotNull('member_id')
            ->with('member')
            ->get();

        if ($receipts->isEmpty()) {
            $this->info('Tidak ada kwitansi pembayaran mandiri untuk di-refresh.');
            return self::SUCCESS;
        }

        $changes = [];
        $skipped = 0;

        foreach ($receipts as $receipt) {
            $member = $receipt->member;

            if (!$member) {
                $skipped++;
                continue; // member sudah dihapus, jangan diutak-atik
            }

            $newName = $member->full_name_with_title;

            if (empty($newName)) {
                $skipped++;
                continue; // jaga-jaga kalau accessor balikin kosong
            }

            if ($newName !== $receipt->payer_name) {
                $changes[] = [
                    'id'  => $receipt->id,
                    'no'  => $receipt->receipt_number,
                    'old' => $receipt->payer_name,
                    'new' => $newName,
                ];

                if (!$dryRun) {
                    $receipt->payer_name = $newName;
                    $receipt->saveQuietly(); // gak ganggu updated_at biar histori tetap rapi
                }
            }
        }

        if (empty($changes)) {
            $this->info('Semua payer_name sudah sesuai (nama + gelar). Tidak ada yang diubah.'
                . ($skipped ? " ({$skipped} dilewati karena member tidak ditemukan)" : ''));
            return self::SUCCESS;
        }

        $this->info(count($changes) . ' kwitansi akan di-update payer_name-nya:');
        foreach ($changes as $c) {
            $this->line("#{$c['id']} ({$c['no']})");
            $this->line('  lama : ' . $c['old']);
            $this->line('  baru : ' . $c['new']);
        }

        if ($skipped) {
            $this->warn("{$skipped} kwitansi dilewati (member tidak ditemukan / nama kosong).");
        }

        if ($dryRun) {
            $this->warn('Mode --dry-run: belum ada yang disimpan.');
        } else {
            $this->info('Selesai disimpan.');
        }

        return self::SUCCESS;
    }
}
