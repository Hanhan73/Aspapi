<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\PaymentBatch;
use App\Models\Receipt;
use App\Services\ReceiptNumberGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillKwitansi extends Command
{
    protected $signature = 'kwitansi:backfill {--dry-run : Cuma tampilkan apa yang akan dibuat, tanpa insert}';

    protected $description = 'Terbitkan nomor kwitansi untuk pembayaran/batch yang sudah verified sebelum fitur kwitansi ada';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $alreadyReceipted = function (int $paymentId) {
            static $cache = null;
            if ($cache === null) {
                $cache = collect();
                Receipt::where('source_type', 'payment')->get()->each(function ($r) use (&$cache) {
                    foreach ($r->payment_id_list as $pid) {
                        $cache->push($pid);
                    }
                });
                $cache = $cache->flip();
            }
            return $cache->has($paymentId);
        };

        // ── 1. Kumpulkan event dari Payment mandiri ────────────────────────────
        $payments = Payment::with('member')
            ->where('status', 'verified')
            ->whereNotNull('verified_at')
            ->orderBy('verified_at')
            ->get()
            ->filter(fn($p) => !$alreadyReceipted($p->id));

        $handledPaymentIds = [];
        $paymentEvents     = [];

        foreach ($payments as $payment) {
            if (in_array($payment->id, $handledPaymentIds)) {
                continue;
            }

            $otherType = $payment->type === 'uang_pangkal' ? 'iuran_tahunan' : 'uang_pangkal';

            $pair = $payments->first(
                fn($p) =>
                $p->id !== $payment->id
                    && $p->member_id === $payment->member_id
                    && $p->type === $otherType
                    && !in_array($p->id, $handledPaymentIds)
            );

            if ($pair) {
                $issuedAt = $payment->verified_at->greaterThan($pair->verified_at)
                    ? $payment->verified_at
                    : $pair->verified_at;

                $paymentEvents[] = [
                    'issued_at' => $issuedAt,
                    'data' => [
                        'source_type'     => 'payment',
                        'source_id'       => $payment->id,
                        'member_id'       => $payment->member_id,
                        'payment_id_list' => [$payment->id, $pair->id],
                        'amount'          => $payment->amount + $pair->amount,
                        'payer_name'      => $payment->member->full_name_with_title ?? '-',
                        'purpose'         => 'Uang Pangkal + Iuran Tahunan Anggota ASPAPI',
                        'issued_by'       => $payment->verified_by,
                    ],
                ];

                $handledPaymentIds[] = $payment->id;
                $handledPaymentIds[] = $pair->id;
                continue;
            }

            $purpose = $payment->type === 'uang_pangkal'
                ? 'Uang pangkal anggota ASPAPI'
                : 'Iuran tahunan anggota ASPAPI tahun ' . $payment->payment_year;

            $paymentEvents[] = [
                'issued_at' => $payment->verified_at,
                'data' => [
                    'source_type'     => 'payment',
                    'source_id'       => $payment->id,
                    'member_id'       => $payment->member_id,
                    'payment_id_list' => [$payment->id],
                    'amount'          => $payment->amount,
                    'payer_name'      => $payment->member->full_name_with_title ?? '-',
                    'purpose'         => $purpose,
                    'issued_by'       => $payment->verified_by,
                ],
            ];

            $handledPaymentIds[] = $payment->id;
        }

        // ── 2. Kumpulkan event dari batch kolektif ─────────────────────────────
        $batches = PaymentBatch::with('region', 'payments')
            ->where('status', 'verified')
            ->whereNotNull('verified_at')
            ->get()
            ->filter(fn($b) => !Receipt::where('source_type', 'payment_batch')->where('source_id', $b->id)->exists());

        $batchEvents = $batches->map(fn($batch) => [
            'issued_at' => $batch->verified_at,
            'data' => [
                'source_type'     => 'payment_batch',
                'source_id'       => $batch->id,
                'region_id'       => $batch->region_id,
                'payment_id_list' => $batch->payments->pluck('id')->toArray(),
                'amount'          => $batch->total_amount,
                'payer_name'      => 'ASPAPI Daerah ' . ($batch->region->province ?? $batch->region->name ?? '-')
                    . ' (' . $batch->member_count . ' anggota)',
                'purpose'         => 'Iuran tahunan kolektif ' . $batch->member_count . ' anggota tahun ' . $batch->payment_year,
                'issued_by'       => $batch->verified_by,
            ],
        ])->all();

        // ── 3. Gabung & urutkan kronologis, biar nomor urut & tahun konsisten ──
        $events = collect(array_merge($paymentEvents, $batchEvents))
            ->sortBy(fn($e) => $e['issued_at']->timestamp)
            ->values();

        if ($events->isEmpty()) {
            $this->info('Tidak ada pembayaran/batch verified yang belum punya kwitansi. Semua sudah lengkap.');
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$events->count()} kwitansi yang akan diterbitkan:");
        $this->table(
            ['Tanggal Verifikasi', 'Jenis', 'Penerima', 'Jumlah'],
            $events->map(fn($e) => [
                $e['issued_at']->format('d M Y H:i'),
                $e['data']['source_type'],
                $e['data']['payer_name'],
                'Rp ' . number_format($e['data']['amount'], 0, ',', '.'),
            ])->toArray()
        );

        if ($dryRun) {
            $this->warn('Mode --dry-run: tidak ada yang disimpan ke database.');
            return self::SUCCESS;
        }

        if (!$this->confirm('Lanjutkan terbitkan ' . $events->count() . ' kwitansi di atas?', true)) {
            $this->warn('Dibatalkan.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($events->count());
        $bar->start();

        DB::transaction(function () use ($events, $bar) {
            foreach ($events as $event) {
                $receipt = ReceiptNumberGenerator::issue($event['data'], $event['issued_at']);
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info('Selesai. ' . $events->count() . ' kwitansi berhasil diterbitkan.');

        return self::SUCCESS;
    }
}
