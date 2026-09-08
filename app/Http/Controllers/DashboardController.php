<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        //statistik
        $stats = [
            'total_server' => 24,
            'total_aplikasi' => 20,
            'monitoring' => 24,
            'maintenance' => 3,
            'alerts' => 5,
        ];

        //data server
        $servers = [
            ['name' => 'SV-R - 001', 'ip' => '103.231.3.01', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
            ['name' => 'SV-R - 002', 'ip' => '103.231.3.02', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '12d 22h'],
            ['name' => 'SV-R - 003', 'ip' => '103.231.3.03', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '9d 11h'],
            ['name' => 'SV-R - 004', 'ip' => '103.231.3.04', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '32d 1h'],
            ['name' => 'SV-R - 005', 'ip' => '103.231.3.05', 'status' => 'Offline', 'cpu' => '---', 'ram' => '---', 'disk' => '---', 'uptime' => '---'],
            ['name' => 'SV-R - 006', 'ip' => '103.231.3.06', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '9d 11h'],
        ];

        //data aplikasi
        $applications = [
            ['name' => 'GTK - Guru', 'server' => 'SV-R - 001', 'status' => 'Aktif', 'domain' => 'webGTK.com', 'licenses' => 'Microsoft SQL Server', 'maintenance' => '20 Agustus 2026'],
            ['name' => 'GTK - Pendidikan', 'server' => 'SV-R - 002', 'status' => 'Aktif', 'domain' => 'webGTK.com', 'licenses' => 'Django', 'maintenance' => '20 Agustus 2026'],
            ['name' => 'GTK - TKA', 'server' => 'SV-R - 003', 'status' => 'Aktif', 'domain' => 'webGTK.com', 'licenses' => 'Microsoft SQL Server', 'maintenance' => '20 Agustus 2026'],
            ['name' => 'GTK - PPPK', 'server' => 'SV-R - 004', 'status' => 'Aktif', 'domain' => 'webGTK.com', 'licenses' => 'Microsoft 365', 'maintenance' => '20 Agustus 2026'],
            ['name' => 'GTK - Kemendikdasmen', 'server' => 'SV-R - 005', 'status' => 'Aktif', 'domain' => 'webGTK.com', 'licenses' => 'Oracle', 'maintenance' => '20 Agustus 2026'],
        ];

        return view('pages.dashboard', compact('stats', 'servers', 'applications'));
    }
}