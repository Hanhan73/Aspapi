<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberNewBatchSeeder extends Seeder
{
    /**
     * Surat Keterangan No. 09/ASPAPI/KT/V/2026
     * 67 anggota baru terdaftar sejak 05 Mei 2026
     * Semua dari Sulawesi Selatan (UMM & PNUP Makassar)
     *
     * Tanpa akun user — email diisi manual oleh admin.
     * Status langsung active karena sudah resmi terdaftar.
     */
    public function run(): void
    {
        // [member_number, nama, instansi]
        $members = [
            ['73062260284', 'MITHA ASTUTI',               'UMM Makassar'],
            ['73062260285', 'YUSDAYANTI',                  'UMM Makassar'],
            ['73711260286', 'A. AS\'ADUL ISLAM MAKMUR',   'UMM Makassar'],
            ['73062260287', 'DEWI RATNASARI HARWAN',       'UMM Makassar'],
            ['73711260288', 'CAKRA NEGARA',                'UMM Makassar'],
            ['73062260289', 'A. ADE AMRINI',               'UMM Makassar'],
            ['73712260290', 'FITRIYANI MUCHTAR',           'UMM Makassar'],
            ['73062260291', 'HARLINDA',                    'UMM Makassar'],
            ['73061260292', 'DZULQAIDAR NURJAM',           'UMM Makassar'],
            ['73712260293', 'SITTI CHADIJAH',              'UMM Makassar'],
            ['73711260294', 'ANDI ILHAM MATTALATTA',       'UMM Makassar'],
            ['73062260295', 'ANDI NOVI INDASARI',          'UMM Makassar'],
            ['73711260296', 'SYAMSUL',                     'UMM Makassar'],
            ['73712260297', 'BESSE MAGHFIRA',              'UMM Makassar'],
            ['73711260298', 'YUSRI YUSUF',                 'UMM Makassar'],
            ['73712260299', 'AINUN NISA RAMADANI',         'UMM Makassar'],
            ['73062260300', 'SUHENA',                      'UMM Makassar'],
            ['73062260301', 'JUMRIATI',                    'UMM Makassar'],
            ['73061260302', 'MUHAMMAD IRFAN BASRI',        'UMM Makassar'],
            ['73712260303', 'ANDI WIDIAWATI',              'UMM Makassar'],
            ['73061260304', 'ANDI BAYU USWATUN KHAZANAH', 'UMM Makassar'],
            ['73061260305', 'M. ZARKASIH YUNUS',           'UMM Makassar'],
            ['73062260306', 'RAHMAWATI',                   'UMM Makassar'],
            ['73712260307', 'SURYANI PRAWITA SARI',        'UMM Makassar'],
            ['73712260308', 'ROSDIANA',                    'UMM Makassar'],
            ['73062260309', 'SABRIA',                      'UMM Makassar'],
            ['73712260310', 'NUR ALIA',                    'UMM Makassar'],
            ['73061260311', 'ABD. RAHMAN BP',              'UMM Makassar'],
            ['73062260312', 'IDA ARIYANI AS.',             'UMM Makassar'],
            ['73061260313', 'NASARUDDIN',                  'UMM Makassar'],
            ['73062260314', 'JUMRA WATI',                  'UMM Makassar'],
            ['73061260315', 'KAHAR',                       'UMM Makassar'],
            ['73711260316', 'MUH. IMADUDDIN',              'UMM Makassar'],
            ['73711260317', 'ZATRIAWAN',                   'UMM Makassar'],
            ['73712260318', 'MARYAM',                      'UMM Makassar'],
            ['73711260319', 'RAHMAN',                      'UMM Makassar'],
            ['73062260320', 'SYAM SUNNIATI',               'UMM Makassar'],
            ['73062260321', 'ERMITA YORI SUKARNO',         'UMM Makassar'],
            ['73711260322', 'KHAERUDDIN MAKKASAU',         'UMM Makassar'],
            ['73711260323', 'AHMAD RIJAL',                 'UMM Makassar'],
            ['73061260324', 'ZULFIKAR',                    'UMM Makassar'],
            ['73061260325', 'HARDIANSYAH',                 'UMM Makassar'],
            ['73711260326', 'WAIZ FIRDAUS',                'UMM Makassar'],
            ['73712260327', 'TRY INDRIYANI PERSADA',       'UMM Makassar'],
            ['73712260328', 'IRMA RIANA RAHMAN',           'UMM Makassar'],
            ['73712260329', 'SITTI HAJAR M. ARSYAD',       'UMM Makassar'],
            ['73061260330', 'AKHMAD AFFANDI',              'UMM Makassar'],
            ['73712260331', 'RISMAWATI',                   'UMM Makassar'],
            ['73712260332', 'NURUL ATHIRAH',               'UMM Makassar'],
            ['73062260333', 'HARTATI',                     'UMM Makassar'],
            ['73062260334', 'HARLIJSAH',                   'UMM Makassar'],
            ['73061260335', 'AKBAR RIYANSYAH',             'UMM Makassar'],
            ['73061260336', 'MUHAMMAD FAKHRUL',            'UMM Makassar'],
            ['73061260337', 'JUSRI ADI',                   'UMM Makassar'],
            ['73712260338', 'NURSINAH',                    'UMM Makassar'],
            ['73061260339', 'ABUSTAN',                     'UMM Makassar'],
            ['73062260340', 'ENDAH LISWATI ALSAM',         'UMM Makassar'],
            ['73712260341', 'HASNA',                       'UMM Makassar'],
            ['73712260342', 'IHKA RIKA PRIMASASTRY',       'UMM Makassar'],
            ['73712260343', 'HARTINI NANDA',               'UMM Makassar'],
            ['73711260344', 'ANDI MUHAMMAD FIKRI',         'UMM Makassar'],
            ['73712260345', 'WILDANI HARUN',               'UMM Makassar'],
            ['73061260346', 'IMRAN NURUL ARIFIN',          'UMM Makassar'],
            ['73061260347', 'HARUN M.',                    'UMM Makassar'],
            ['73061260348', 'WAHYUDI MALIK',               'UMM Makassar'],
            ['73711260350', 'NAHIRUDDIN',                  'PNUP Ujung Pandang'],
            ['73712260351', 'KURNIATI ASMAR',              'PNUP Ujung Pandang'],
        ];

        // Province ID Sulawesi Selatan
        $provinceId = \App\Models\Province::where('name', 'Sulawesi Selatan')->value('id');

        $imported = 0;
        $skipped  = 0;

        foreach ($members as [$memberNumber, $name, $institution]) {

            if (Member::where('member_number', $memberNumber)->exists()) {
                $this->command->line("  Skip (sudah ada): {$memberNumber} — {$name}");
                $skipped++;
                continue;
            }

            DB::transaction(function () use (
                $memberNumber, $name, $institution, $provinceId, &$imported
            ) {
                // Buat member TANPA user_id — admin input email nanti
                $member = Member::create([
                    'user_id'           => null,
                    'full_name'         => $name,
                    'email'             => null,
                    'institution'       => $institution,
                    'member_type'       => 'biasa',
                    'registration_type' => 'baru',
                    'claims_old_member' => false,
                    'claimed_join_year' => 2026,
                    'member_number'     => $memberNumber,
                    'province_id'       => $provinceId,
                    'biodata_status'    => 'verified',
                    'status'            => 'active',       // langsung aktif
                    'dues_paid'         => true,
                    'dues_paid_at'      => Carbon::parse('2026-05-05'),
                    'active_until'      => Carbon::parse('2027-05-05'),
                    'registered_at'     => Carbon::parse('2026-05-05'),
                ]);

                // Catat pembayaran uang pangkal
                Payment::create([
                    'member_id'      => $member->id,
                    'type'           => 'uang_pangkal',
                    'payment_method' => 'kolektif',
                    'amount'         => 250000,
                    'status'         => 'verified',
                    'verified_at'    => Carbon::parse('2026-05-05'),
                    'payment_year'   => 2026,
                ]);

                $imported++;
            });
        }

        $this->command->info("Import selesai: {$imported} ditambahkan, {$skipped} dilewati.");
        $this->command->line("Referensi: Surat No. 09/ASPAPI/KT/V/2026 tanggal 05 Mei 2026.");
        $this->command->warn("Email 67 anggota ini kosong — perlu diisi manual via admin.");
    }
}