<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use Illuminate\Support\Str;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name' => 'ASPAPI Sumatera Utara',   'province' => 'Sumatera Utara',   'chairperson' => 'Dodi Pramana, S.Sos., M.Si.',              'period' => '2020-2024'],
            ['name' => 'ASPAPI Sumatera Barat',   'province' => 'Sumatera Barat',   'chairperson' => 'Nini Loma, S.Pd.',                          'period' => '2022-2026'],
            ['name' => 'ASPAPI Sumatera Selatan', 'province' => 'Sumatera Selatan', 'chairperson' => 'Kiki Eva Maria, SE., S.Pd., M.Pd.',          'period' => '2023-2027'],
            ['name' => 'ASPAPI Kepulauan Riau',   'province' => 'Kepulauan Riau',   'chairperson' => 'Andika Yanuar, S.Pd., Gr.',                  'period' => '2022-2026'],
            ['name' => 'ASPAPI DKI Jakarta',      'province' => 'DKI Jakarta',      'chairperson' => 'Marsofiyati, M.Pd.',                         'period' => '2023-2027'],
            ['name' => 'ASPAPI Jawa Barat',       'province' => 'Jawa Barat',       'chairperson' => 'Prof. Dr. H. Edi Suryadi, M.Si.',            'period' => '2023-2027'],
            ['name' => 'ASPAPI Jawa Tengah',      'province' => 'Jawa Tengah',      'chairperson' => 'Prof. Dr. S. Martono, M.Si.',                'period' => '2023-2027'],
            ['name' => 'ASPAPI DI Yogyakarta',    'province' => 'DI Yogyakarta',    'chairperson' => 'Dr. Sutirman, M.Pd.',                        'period' => '2022-2026'],
            ['name' => 'ASPAPI Jawa Timur',       'province' => 'Jawa Timur',       'chairperson' => 'Dr. Heny Kusdianty, M.M.',                   'period' => '2022-2026'],
            ['name' => 'ASPAPI Sulawesi Selatan', 'province' => 'Sulawesi Selatan', 'chairperson' => 'Dr. Risma Niswaty, S.S., M.Si.',             'period' => '2020-2024'],
        ];

        foreach ($regions as $r) {
            Region::updateOrCreate(
                ['name' => $r['name']],
                [
                    'slug'        => Str::slug($r['name']),
                    'province'    => $r['province'],
                    'chairperson' => $r['chairperson'],
                    'description' => 'Periode Ketua: ' . $r['period'],
                    'is_active'   => true,
                    'sort_order'  => 0,
                ]
            );
        }
    }
}