<?php

namespace Database\Seeders;

use App\Models\Board;
use Illuminate\Database\Seeder;

class BoardSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'category' => 'Ketua',
                'members'  => [
                    ['name' => 'Dr. Rasto, M.Pd.',                                               'position' => 'Ketua Umum',   'institution' => 'Universitas Pendidikan Indonesia'],
                    ['name' => 'Dra. Armida Silvia, M.Si., QPOA., CESP.',                        'position' => 'Ketua I',      'institution' => 'Universitas Negeri Padang'],
                    ['name' => 'Dr. Patni Ninghardjanti, M.Pd.',                                 'position' => 'Ketua II',     'institution' => 'Universitas Sebelas Maret'],
                    ['name' => 'Drs. H.M. Jamil Latief, M.M., M.Pd., QPOA., CESP., CAP., COSP', 'position' => 'Ketua III',    'institution' => 'Universitas Muhammadiyah Prof. Dr. Hamka'],
                ],
            ],
            [
                'category' => 'Sekretaris',
                'members'  => [
                    ['name' => 'Muh. Darwis, M.Pd., QPOA. CESP.',          'position' => 'Sekretaris Jenderal', 'institution' => 'Universitas Negeri Makassar'],
                    ['name' => 'Ahmad Saeroji, S.Pd., M.Pd.',               'position' => 'Sekretaris I',        'institution' => 'Universitas Negeri Semarang'],
                    ['name' => 'Marsofiyati, M.Pd., QPOA., CESP.',          'position' => 'Sekretaris II',       'institution' => 'Universitas Negeri Jakarta'],
                    ['name' => 'Dra. Imasita, M.Si., QPOA, CESP, CAP, COSP','position' => 'Sekretaris III',      'institution' => 'Politeknik Negeri Ujung Pandang'],
                ],
            ],
            [
                'category' => 'Bendahara',
                'members'  => [
                    ['name' => 'Dewi Nurmalasari, M.M.',                                      'position' => 'Bendahara Umum', 'institution' => 'Universitas Negeri Jakarta'],
                    ['name' => 'Prof. Dr. Cicilia Dyah Sulistyaningrum Indrawati, M.Pd.',     'position' => 'Bendahara I',    'institution' => 'Universitas Sebelas Maret'],
                    ['name' => 'Dr. Siti Umi Khayatun Mardiyah, M.Pd.',                      'position' => 'Bendahara II',   'institution' => 'Universitas Negeri Yogyakarta'],
                    ['name' => 'Sitti Hardiyanti Arhas, M.Pd., QPOA., CAP., COSP.',          'position' => 'Bendahara III',  'institution' => 'Universitas Negeri Makassar'],
                ],
            ],
            [
                'category' => 'Departemen Pengembangan Organisasi',
                'members'  => [
                    ['name' => 'Jamaluddin, S.Pd., M.Si. CRA., CRP.',  'position' => 'Kepala Departemen', 'institution' => 'Universitas Negeri Makassar'],
                    ['name' => 'Muslikhah Dwihartanti, M.Pd.',          'position' => 'Anggota',           'institution' => 'Universitas Negeri Yogyakarta'],
                    ['name' => 'Enjang Suhaedin, S.Pd., Gr., M.M.',    'position' => 'Anggota',           'institution' => 'SMK Negeri 7 Batam'],
                    ['name' => 'Mulyadi Yusuf, S.A.P., M.Si., QPOA.', 'position' => 'Anggota',           'institution' => 'Politeknik Negeri Ujung Pandang'],
                    ['name' => 'Sufriadi, S.Pd., QPOA., CAP.',         'position' => 'Anggota',           'institution' => 'SMK Negeri 1 Sinjai Sulawesi Selatan'],
                ],
            ],
            [
                'category' => 'Departemen Penelitian dan Publikasi Ilmiah',
                'members'  => [
                    ['name' => 'Arwan Nur Ramadhan, M.Pd.',              'position' => 'Kepala Departemen', 'institution' => 'Universitas Negeri Yogyakarta'],
                    ['name' => 'Dr. Rino, S.Pd., M.Pd., M.M.',           'position' => 'Anggota',           'institution' => 'Universitas Negeri Padang'],
                    ['name' => 'Drs. Hirman, M.Si., QPOA., CESP., CAP.','position' => 'Anggota',           'institution' => 'Politeknik Negeri Ujung Pandang'],
                    ['name' => 'Drs. Sarimin',                            'position' => 'Anggota',           'institution' => 'SMK Negeri 1 Tanjung Pinang'],
                    ['name' => 'Agung Kuswantoro, S.Pd., M.Pd.',          'position' => 'Anggota',           'institution' => 'Universitas Negeri Semarang'],
                ],
            ],
            [
                'category' => 'Departemen Kerjasama',
                'members'  => [
                    ['name' => 'Drs. Iwan Giwangkara, M.M.',            'position' => 'Kepala Departemen', 'institution' => 'Praktisi DKI Jakarta'],
                    ['name' => 'Prof. Dr. Budi Santoso, M.Si.',          'position' => 'Anggota',           'institution' => 'Universitas Pendidikan Indonesia'],
                    ['name' => 'Sri Arita, S.Pd., M.Pd. E.',             'position' => 'Anggota',           'institution' => 'Universitas Negeri Padang'],
                    ['name' => 'Durinda Puspasari, M.Pd.',               'position' => 'Anggota',           'institution' => 'Universitas Negeri Surabaya'],
                    ['name' => 'Dr. Nasaruddin H., S.Pd., S.AN., M.Pd.','position' => 'Anggota',           'institution' => 'Universitas Negeri Makassar'],
                ],
            ],
            [
                'category' => 'Departemen Hukum dan Advokasi',
                'members'  => [
                    ['name' => 'Prof. Dr. H. Andi Sukri Syamsuri, M. Hum., QPOA.', 'position' => 'Kepala Departemen', 'institution' => 'Universitas Muhammadiyah Makassar'],
                    ['name' => 'Dr. Hj. Ehsana El Khuluqo, M.Pd.',                 'position' => 'Anggota',           'institution' => 'Universitas Muhammadiyah Prof. Dr. Hamka'],
                    ['name' => 'Durinta Puspasari, M.Pd.',                          'position' => 'Anggota',           'institution' => 'Universitas Negeri Surabaya'],
                    ['name' => 'Dra. Sri Mutmainnah, M.Si.',                        'position' => 'Anggota',           'institution' => 'Universitas Negeri Medan'],
                    ['name' => 'Rian Candra Dinata, S.Pd.',                         'position' => 'Anggota',           'institution' => 'SMK Negeri 2 Pagar Alam Sumsel'],
                ],
            ],
            [
                'category' => 'Departemen Pendidikan dan Pelatihan',
                'members'  => [
                    ['name' => 'Dr. Agus Hermawan, GradDipMgt., M.Si., M.Bus.', 'position' => 'Anggota', 'institution' => 'Universitas Negeri Malang'],
                    ['name' => 'Dr. Yuhendry Leo Vrista, M.Pd.',                 'position' => 'Anggota', 'institution' => 'Universitas Negeri Padang'],
                    ['name' => 'Darma Rika Swaramarinda, S.Pd., M.SE.',          'position' => 'Anggota', 'institution' => 'Universitas Negeri Jakarta'],
                    ['name' => 'Dra. Lina Marlina, M.Pd.',                       'position' => 'Anggota', 'institution' => 'Praktisi Jawa Barat'],
                    ['name' => 'H. Muhammad Rusdi, SE., M.Si., QPOA., CESP.',    'position' => 'Anggota', 'institution' => 'Universitas Muhammadiyah Makassar'],
                ],
            ],
            [
                'category' => 'Departemen Humas dan Sistem Informasi',
                'members'  => [
                    ['name' => 'Dr. Drs. Edy Ramon Torong, SH., MM.',  'position' => 'Anggota', 'institution' => 'Praktisi DKI Jakarta'],
                    ['name' => 'Yogi Kardillah, SKM., M. Kes.',         'position' => 'Anggota', 'institution' => 'SMK Muhammadiyah Pagar Alam Sumsel'],
                    ['name' => 'Andi Gunawan, S.Si., M. Kom., QPOA.',   'position' => 'Anggota', 'institution' => 'Politeknik Negeri Ujung Pandang'],
                    ['name' => 'Drs. Mohammad Arif, M.Pd.',             'position' => 'Anggota', 'institution' => 'Universitas Negeri Malang'],
                    ['name' => 'Deni Darmawan, SE., M.Si.',             'position' => 'Anggota', 'institution' => 'Universitas Tanjungpura Kalbar'],
                ],
            ],
            [
                'category' => 'Departemen Sertifikasi',
                'members'  => [
                    ['name' => 'Dr. Sambas Ali Muhidin, M.Si., QPOA.', 'position' => 'Anggota', 'institution' => 'Universitas Pendidikan Indonesia'],
                    ['name' => 'Dra. Dewi Setiati',                     'position' => 'Anggota', 'institution' => 'Praktisi Jawa Barat'],
                    ['name' => 'Ishak Suhada, S.T., M.Si.',             'position' => 'Anggota', 'institution' => 'Universitas Tanjungpura Kalbar'],
                    ['name' => 'Ani Ismarini, S.Pd., Gr., QPOA',        'position' => 'Anggota', 'institution' => 'SMK Negeri 2 Pagar Alam Sumsel'],
                    ['name' => 'Wahyu Rusdiyanto, M.M.',                'position' => 'Anggota', 'institution' => 'Universitas Negeri Yogyakarta'],
                ],
            ],
        ];

        $sortOrder = 1;
        foreach ($groups as $group) {
            foreach ($group['members'] as $member) {
                Board::create([
                    'name'              => $member['name'],
                    'position'          => $member['position'],
                    'position_category' => $group['category'],
                    'institution'       => $member['institution'],
                    'period'            => '2022-2026',
                    'is_active'         => true,
                    'sort_order'        => $sortOrder++,
                ]);
            }
        }
    }
}