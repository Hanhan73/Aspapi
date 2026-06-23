<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@aspapi.id'],
            [
                'name'           => 'Super Admin',
                'password'       => Hash::make('ganti-password-ini!'),
                'role'           => 'superadmin',
                'email_verified' => true,
            ]
        );

        $this->command->info('Superadmin berhasil dibuat: superadmin@aspapi.id');
        $this->command->warn('Jangan lupa ganti password lewat panel atau tinker!');
    }
}