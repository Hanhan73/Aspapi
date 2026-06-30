<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Console\Command;

class RefreshReceiptPurpose extends Command
{
    protected $signature = 'kwitansi:refresh-purpose {--dry-run : Cuma tampilkan perubahan, tanpa simpan}';

    protected $description = 'Update teks kolom purpose di kwitansi yang sudah ada (format rentang tanggal iuran), tanpa ubah nomor/tanggal terbit';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $receipts = Receipt::where('source_type', 'payment')
            ->with('member')
            ->get();

        if ($receipts->isEmpty()) {
            $this->info('Tidak ada kwitansi pembayaran mandiri untuk di-refresh.');
            return self::SUCCESS;
        }

        $changes = [];

        foreach ($receipts as $receipt) {
            $member = $receipt->member;
            if (!$member) {
                continue; // member sudah dihapus, skip
            }

            $paymentIds = $receipt->payment_id_list ?? [];
            $payments   = Payment::whereIn('id', $paymentIds)->get();

            $hasPangkal = $payments->contains('type', 'uang_pangkal');
            $hasIuran   = $payments->contains('type', 'iuran_tahunan');

            $iuranPeriodText = function () use ($member) {
                if (!$member->active_until) {
                    return 'Biaya perpanjangan anggota ASPAPI untuk satu tahun';
                }
                $end   = $member->active_until->copy();
                $start = $end->copy()->subYear();

                return 'Biaya perpanjangan anggota ASPAPI untuk satu tahun ('
                    . $start->translatedFormat('d F Y') . ' s.d. ' . $end->translatedFormat('d F Y') . ')';
            };

            if ($hasPangkal && $hasIuran) {
                $newPurpose = 'Uang Pangkal Anggota ASPAPI + ' . $iuranPeriodText();
            } elseif ($hasIuran) {
                $newPurpose = $iuranPeriodText();
            } elseif ($hasPangkal) {
                $newPurpose = 'Uang pangkal anggota ASPAPI';
            } else {
                continue; // gak ketauan jenisnya, jangan diutak-atik
            }

            if ($newPurpose !== $receipt->purpose) {
                $changes[] = [
                    'id'  => $receipt->id,
                    'no'  => $receipt->receipt_number,
                    'old' => $receipt->purpose,
                    'new' => $newPurpose,
                ];

                if (!$dryRun) {
                    $receipt->purpose = $newPurpose;
                    $receipt->saveQuietly(); // gak ganggu updated_at biar histori tetap rapi
                }
            }
        }

        if (empty($changes)) {
            $this->info('Semua kwitansi sudah pakai format purpose terbaru. Tidak ada yang diubah.');
            return self::SUCCESS;
        }

        $this->info(count($changes) . ' kwitansi akan di-update teks purpose-nya:');
        foreach ($changes as $c) {
            $this->line("#{$c['id']} ({$c['no']})");
            $this->line('  lama : ' . $c['old']);
            $this->line('  baru : ' . $c['new']);
        }

        if ($dryRun) {
            $this->warn('Mode --dry-run: belum ada yang disimpan.');
        } else {
            $this->info('Selesai disimpan.');
        }

        return self::SUCCESS;
    }
}
