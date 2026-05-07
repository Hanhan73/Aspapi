<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberRenewalSeeder extends Seeder
{
    /**
     * Surat Keterangan No. 10/ASPAPI/KT/V/2026
     * 15 anggota yang telah perpanjang masa aktif
     * Berlaku: 05 Mei 2026 s/d 05 Mei 2027
     */
    public function run(): void
    {
        // [NIA/member_number, nama (untuk konfirmasi log)]
        $renewals = [
            '73062110020', // RISMA NISWATY
            '73061110011', // MUH. DARWIS
            '73712200010', // SITTI HARDIYANTI ARHAS
            '73061210277', // H. ANDI SUKRI SYAMSURI
            '73061210278', // SAHABUDDIN N.
            '73061210279', // SYAMSUDDIN
            '73711210280', // MUH. ALKA
            '73711210281', // TRY GUSTAF SAID
            '73062210282', // SITTI FATIMAH
            '73712210283', // AZLINDA
            '73712200046', // IMASITA
            '73711200061', // HIRMAN
            '73711200349', // ANDI GUNAWAN
            '73711200350', // SERPIAN
            '73712200351', // ASIMA
        ];

        $updated  = 0;
        $notFound = 0;

        foreach ($renewals as $memberNumber) {
            $member = Member::where('member_number', $memberNumber)->first();

            if (! $member) {
                $this->command->warn("Tidak ditemukan: {$memberNumber}");
                $notFound++;
                continue;
            }

            DB::transaction(function () use ($member) {
                // Update status member
                $member->update([
                    'status'       => 'active',
                    'dues_paid'    => true,
                    'dues_paid_at' => Carbon::parse('2026-05-05'),
                    'active_until' => Carbon::parse('2027-05-05'),
                ]);

                // Catat pembayaran iuran tahunan
                Payment::updateOrCreate(
                    [
                        'member_id'    => $member->id,
                        'type'         => 'iuran_tahunan',
                        'payment_year' => 2026,
                    ],
                    [
                        'payment_method' => 'kolektif',
                        'amount'         => 120000,
                        'status'         => 'verified',
                        'verified_at'    => Carbon::parse('2026-05-05'),
                    ]
                );
            });

            $updated++;
        }

        $this->command->info("Perpanjangan selesai: {$updated} diperbarui, {$notFound} tidak ditemukan.");
        $this->command->line("Referensi: Surat No. 10/ASPAPI/KT/V/2026 tanggal 05 Mei 2026.");
    }
}