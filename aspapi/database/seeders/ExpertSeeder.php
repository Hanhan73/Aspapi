<?php

namespace Database\Seeders;

use App\Models\Expert;
use Illuminate\Database\Seeder;

class ExpertSeeder extends Seeder
{
    public function run(): void
    {
        // expertise dipakai sebagai pengelompok di halaman publik:
        // 'Pimpinan' → Pimpinan Dewan Pakar
        // 'Guru Besar' → Unsur Guru Besar
        // 'Ketua Prodi' → Unsur Ketua Prodi

        $data = [
            // Pimpinan
            ['name' => 'Prof. Dr. H. Suwatno, M.Si.',  'title' => 'Ketua Dewan Pakar Periode 2022–2026',    'expertise' => 'Pimpinan', 'institution' => 'Universitas Pendidikan Indonesia', 'sort_order' => 1],
            ['name' => 'Dhidik Apriyanto, SE., M.Si.',  'title' => 'Sekretaris Dewan Pakar Periode 2022–2026','expertise' => 'Pimpinan', 'institution' => 'Universitas Tanjungpura',          'sort_order' => 2],

            // Guru Besar
            ['name' => 'Prof. Muhyadi',                              'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Negeri Yogyakarta',     'sort_order' => 10],
            ['name' => 'Prof. Dr. S. Martono, M.Si.',                'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Negeri Semarang',      'sort_order' => 11],
            ['name' => 'Prof. Dr. Bambang Suratman, M.Pd.',          'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Negeri Surabaya',      'sort_order' => 12],
            ['name' => 'Prof. Dr. Budi Eko Soetjipto, M.Ed., M.Si.','title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Negeri Malang',        'sort_order' => 13],
            ['name' => 'Prof. Dr. Tjutju Yuniarsih, SE., M.Pd.',    'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Pendidikan Indonesia',  'sort_order' => 14],
            ['name' => 'Prof. Dr. Wiedy Murtini, M.Pd.',            'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Sebelas Maret',        'sort_order' => 15],
            ['name' => 'Prof. Dr. H. A. Sobandi, M.Si., M.Pd.',     'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Pendidikan Indonesia',  'sort_order' => 16],
            ['name' => 'Prof. Dr. Haedar Akib, M.Si.',              'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Negeri Makassar',      'sort_order' => 17],
            ['name' => 'Prof. Dr. H. Edi Suryadi, M.Si.',           'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Pendidikan Indonesia',  'sort_order' => 18],
            ['name' => 'Prof. Dr. Dra. Hj. Janah Sojanah, M.Si.',   'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Pendidikan Indonesia',  'sort_order' => 19],
            ['name' => 'Prof. Dr. Hj. Nani Sutarni, M.Pd.',         'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Pendidikan Indonesia',  'sort_order' => 20],
            ['name' => 'Prof. Dr. Henry Eryanto, M.M.',             'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Negeri Jakarta',       'sort_order' => 21],
            ['name' => 'Prof. Dr. Budi Santoso, M.Si.',             'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Pendidikan Indonesia',  'sort_order' => 22],
            ['name' => 'Prof. Dr. Dedi Purwana, S.E., M.Bus.',      'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Negeri Jakarta',       'sort_order' => 23],
            ['name' => 'Prof. Dr. Drs. Saliman, M.Pd.',             'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Negeri Yogyakarta',    'sort_order' => 24],
            ['name' => 'Prof. Dr. Cicilia Dyah S. I., M.Pd.',       'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Sebelas Maret',        'sort_order' => 25],
            ['name' => 'Prof. Dr. Endang Supardi, M.Si.',           'title' => 'Anggota', 'expertise' => 'Guru Besar', 'institution' => 'Universitas Pendidikan Indonesia',  'sort_order' => 26],

            // Ketua Prodi
            ['name' => 'Dr. Rosidah, M.Si.',                        'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'Universitas Negeri Yogyakarta',    'sort_order' => 30],
            ['name' => 'Ahmad Nurkhin, S.Pd., M.Si.',               'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'Universitas Negeri Semarang',     'sort_order' => 31],
            ['name' => 'Dr. Heri Sawiji, M.Pd.',                    'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'Universitas Sebelas Maret',       'sort_order' => 32],
            ['name' => 'Roni Faslah, S.Pd., M.M.',                  'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'Universitas Negeri Jakarta',      'sort_order' => 33],
            ['name' => 'Dr. Christian Wiradendi Wohor',              'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'Universitas Negeri Jakarta',      'sort_order' => 34],
            ['name' => 'Triesninda Pahlevi, S.Pd., M.Pd.',          'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'Universitas Negeri Surabaya',     'sort_order' => 35],
            ['name' => 'Dr. Madziatul Churiyah, S.Pd., M.M.',       'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'Universitas Negeri Malang',       'sort_order' => 36],
            ['name' => 'Dr. Sirajuddin Saleh, S.Pd., M.Pd.',        'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'Universitas Negeri Makassar',     'sort_order' => 37],
            ['name' => 'Dr. Armiati, S.Pd., M.Pd.',                 'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'Universitas Negeri Padang',       'sort_order' => 38],
            ['name' => 'Nelly Armayanti, SP., MP.',                  'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'Universitas Negeri Medan',        'sort_order' => 39],
            ['name' => 'Dr. Hady Siti Hadijah, S.Pd., M.Si.',       'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'Universitas Pendidikan Indonesia', 'sort_order' => 40],
            ['name' => 'Dra. Asima, M.Si.',                         'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'Politeknik Negeri Ujung Pandang',  'sort_order' => 41],
            ['name' => 'Dr. H. Onny Fitriana Sitorus, M.Pd.',       'title' => 'Anggota', 'expertise' => 'Ketua Prodi', 'institution' => 'UHAMKA Jakarta',                  'sort_order' => 42],
        ];

        foreach ($data as $row) {
            Expert::create([
                'name'        => $row['name'],
                'title'       => $row['title'],
                'expertise'   => $row['expertise'],
                'institution' => $row['institution'],
                'is_active'   => true,
                'sort_order'  => $row['sort_order'],
            ]);
        }
    }
}