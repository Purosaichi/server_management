<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        Pengguna::query()->updateOrCreate(
            ['nama_login' => 'admin@kemendik.go.id'],
            [
                'nama_pengguna' => 'Administrator',
                'kata_sandi' => 'password',
                'status_pengguna' => 'Aktif',
                'keterangan' => 'Akun uji login',
            ]
        );
    }
}
