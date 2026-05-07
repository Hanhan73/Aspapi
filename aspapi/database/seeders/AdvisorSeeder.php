<?php

namespace Database\Seeders;

use App\Models\Advisor;
use Illuminate\Database\Seeder;

class AdvisorSeeder extends Seeder
{
    public function run(): void
    {
        // Isi dengan mantan Ketua Umum ASPAPI — tambahkan data sesuai kebutuhan
        $advisors = [
            ['name' => 'Prof. Dr. H. Suwatno, M.Si.',  'institution' => 'Universitas Pendidikan Indonesia', 'position' => 'Dewan Penasihat'],
            ['name' => 'Dr. H. Didi Suprijadi, M.Pd.', 'institution' => 'Universitas Negeri Malang',        'position' => 'Dewan Penasihat'],
            ['name' => 'Dr. H. Edi Suryadi, M.Si.',   'institution' => 'Universitas Pendidikan Indonesia', 'position' => 'Dewan Penasihat'],
        ];

        foreach ($advisors as $i => $data) {
            Advisor::create([
                'name'        => $data['name'],
                'institution' => $data['institution'],
                'position'    => $data['position'] ?? null,
                'is_active'   => true,
                'sort_order'  => $i + 1,
            ]);
        }
    }
}