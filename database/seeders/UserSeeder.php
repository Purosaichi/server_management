<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@kemendik.go.id'],
            [
                'nama_pengguna' => 'Administrator',
                'kata_sandi' => 'password',
                'status_pengguna' => 'Aktif',
                'keterangan' => 'Akun uji login',
            ]
        );
    }
}
