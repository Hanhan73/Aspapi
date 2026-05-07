<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProvinceSeeder::class,
            RegionSeeder::class,
            AdvisorSeeder::class,
            ExpertSeeder::class,
            BoardSeeder::class,
            MemberImportSeeder::class,
        ]);

        // Admin user
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@aspapi.or.id'],
            [
                'name'           => 'Admin ASPAPI',
                'password'       => bcrypt('password123'),
                'role'           => 'admin',
                'email_verified' => true,
            ]
        );

        // Bendahara user
        \App\Models\User::updateOrCreate(
            ['email' => 'bendahara@aspapi.or.id'],
            [
                'name'           => 'Bendahara ASPAPI',
                'password'       => bcrypt('password123'),
                'role'           => 'bendahara',
                'email_verified' => true,
            ]
        );

        $this->command->info('Seeder selesai!');
    }
}