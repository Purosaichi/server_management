<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        // Data dummy aplikasi
        $applications = [
            [
                'id' => 1,
                'name' => 'GTK - Guru',
                'server' => 'SVR - 001',
                'status' => 'Aktif',
                'domain' => 'webGTK.com',
                'licenses' => 'Aktif',
                'maintenance' => '20 Agustus 2026'
            ],
            [
                'id' => 2,
                'name' => 'GTK - UjianNasional',
                'server' => 'SVR - 002',
                'status' => 'Aktif',
                'domain' => 'webGTK.com',
                'licenses' => 'Aktif',
                'maintenance' => '20 Agustus 2026'
            ],
            [
                'id' => 3,
                'name' => 'GTK - TKA',
                'server' => 'SVR - 001',
                'status' => 'Nonaktif',
                'domain' => 'webGTK.com',
                'licenses' => 'Aktif',
                'maintenance' => '20 Agustus 2026'
            ],
            [
                'id' => 4,
                'name' => 'GTK - PPPK',
                'server' => 'SVR - 001',
                'status' => 'Aktif',
                'domain' => 'webGTK.com',
                'licenses' => 'Akan Expired',
                'maintenance' => '20 Agustus 2026'
            ],
            [
                'id' => 5,
                'name' => 'GTK - Mutasi',
                'server' => 'SVR - 005',
                'status' => 'Aktif',
                'domain' => 'webGTK.com',
                'licenses' => 'Aktif',
                'maintenance' => '20 Agustus 2026'
            ],
            [
                'id' => 6,
                'name' => 'GTK - SIMPKB',
                'server' => 'SVR - 001',
                'status' => 'Aktif',
                'domain' => 'webGTK.com',
                'licenses' => 'Aktif',
                'maintenance' => '20 Agustus 2026'
            ],
        ];

        return view('pages.application.application', compact('applications'));
    }

    public function show($id)
    {
        // Data dummy untuk detail aplikasi
        $application = [
            'id' => $id,
            'name' => 'GTK - Guru',
            'server' => 'SVR - 001',
            'status' => 'Aktif',
            'domain' => 'webGTK.com',
            'licenses' => 'Aktif',
            'maintenance' => '20 Agustus 2026',
            'description' => 'Aplikasi untuk mengelola data guru',
            'version' => '2.14',
            'category' => 'Internal',
            'sla' => '99.5%',
            'uptime' => '100%',
            'last_30_days' => '100%',
            'since' => '15 Januari 2023 (3 Tahun 7 Bulan)',
            'pic' => 'Fathier Assyarief',
            'division' => 'IT Infrastructure',
        ];

        return view('pages.application.detail', compact('application'));
    }
}