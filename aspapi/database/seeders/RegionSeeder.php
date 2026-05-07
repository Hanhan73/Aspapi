<?php
// database/seeders/RegionSeeder.php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['province' => 'Sumatera Utara',   'chairman_name' => 'Dodi Pramana, S.Sos., M.Si.',       'chairman_title' => 'Universitas Negeri Medan',        'period_start' => '2020', 'period_end' => '2024', 'sort_order' => 1],
            ['province' => 'Sumatera Barat',   'chairman_name' => 'Nini Loma, S.Pd.',                  'chairman_title' => 'SMKN Baso Agam Sumbar',           'period_start' => '2022', 'period_end' => '2026', 'sort_order' => 2],
            ['province' => 'Sumatera Selatan', 'chairman_name' => 'Kiki Eva Maria, SE., S.Pd., M.Pd.', 'chairman_title' => 'SMK',                             'period_start' => '2023', 'period_end' => '2027', 'sort_order' => 3],
            ['province' => 'Kepulauan Riau',   'chairman_name' => 'Andika Yanuar, S.Pd., Gr.',         'chairman_title' => 'SMKN 1 Tanjungpinang',            'period_start' => '2022', 'period_end' => '2026', 'sort_order' => 4],
            ['province' => 'DKI Jakarta',      'chairman_name' => 'Marsofiyati, M.Pd.',                'chairman_title' => 'Universitas Negeri Jakarta',       'period_start' => '2023', 'period_end' => '2027', 'sort_order' => 5],
            ['province' => 'Jawa Barat',       'chairman_name' => 'Prof. Dr. H. Edi Suryadi, M.Si.',  'chairman_title' => 'Universitas Pendidikan Indonesia', 'period_start' => '2023', 'period_end' => '2027', 'website_url' => 'https://www.aspapijabar.org', 'sort_order' => 6],
            ['province' => 'Jawa Tengah',      'chairman_name' => 'Prof. Dr. S. Martono, M.Si.',      'chairman_title' => 'Universitas Negeri Semarang',      'period_start' => '2023', 'period_end' => '2027', 'sort_order' => 7],
            ['province' => 'DI Yogyakarta',    'chairman_name' => 'Dr. Sutirman, M.Pd.',              'chairman_title' => 'Universitas Negeri Yogyakarta',    'period_start' => '2022', 'period_end' => '2026', 'sort_order' => 8],
            ['province' => 'Jawa Timur',       'chairman_name' => 'Dr. Heny Kusdianty, M.M.',         'chairman_title' => 'Universitas Negeri Malang',        'period_start' => '2022', 'period_end' => '2026', 'sort_order' => 9],
            ['province' => 'Sulawesi Selatan', 'chairman_name' => 'Dr. Risma Niswaty, S.S., M.Si.',   'chairman_title' => 'Universitas Negeri Makassar',      'period_start' => '2020', 'period_end' => '2024', 'website_url' => 'https://aspapi-sulsel.org', 'sort_order' => 10],
        ];

        foreach ($regions as $data) {
            $slug = \Illuminate\Support\Str::slug($data['province']);

            $region = Region::create(array_merge($data, [
                'name'      => 'ASPAPI ' . $data['province'],
                'slug'      => $slug,
                'is_active' => true,
            ]));

            User::create([
                'name'      => 'ASPAPI ' . $data['province'],
                'email'     => 'daerah.' . $slug . '@aspapi.or.id',
                'password'  => Hash::make('password123'),
                'role'      => 'aspapi_daerah',
                'region_id' => $region->id,
            ]);
        }
    }
}