<?php

namespace App\Http\Controllers;

class ApplicationController extends Controller
{
    /**
     * Halaman daftar aplikasi
     */
    public function index()
    {
        $applications = $this->applications();

        return view('pages.application.application', compact('applications'));
    }

    /**
     * Halaman detail aplikasi
     */
    public function show(int $id)
    {
        // Ambil data dari sumber utama
        $app = collect($this->applications())->firstWhere('id', $id);

        abort_unless($app, 404);

        // Gabungin dengan data detail
        $application = array_merge($app, $this->applicationDetails($app));

        return view('pages.application.detail', compact('application'));
    }

    /**
     * Data detail aplikasi (ngikut data utama)
     */
    private function applicationDetails(array $app): array
    {
        $isDown = $app['status'] === 'Down';

        return [
            'description' => $isDown ? 'Aplikasi sedang tidak tersedia' : 'Aplikasi untuk mengelola sumber daya guru untuk keperluan pendidikan',
            'code' => strtoupper(substr(str_replace([' ', '-'], '', $app['name']), 0, 4)),
            'category' => $isDown ? '-' : 'Internal',
            'sla' => $isDown ? '-' : '99.5%',
            'version' => $isDown ? '-' : '2.14',
            'uptime' => $isDown ? '0%' : '100%',
            'uptime_period' => '30 Hari Terakhir',
            'since' => $isDown ? '-' : '15 Januari 2023',
            'since_duration' => $isDown ? '-' : '(3 Tahun 7 Bulan)',

            // Server & Infrastruktur
            'server' => $app['server_name'],
            'ip_address' => $isDown ? '-' : '127.000.0.10',
            'os' => $isDown ? '-' : 'Windows Server 2022',
            'database' => $isDown ? '-' : 'MySQL 8.0',

            // Domain
            'domain' => $app['domain'] ?? '-',
            'ssl_certificate' => $isDown ? 'INVALID' : 'VALID',
            'ssl_expired' => $isDown ? '-' : '12 Desember 2026',
            'domain_status' => $isDown ? 'Tidak Aktif' : 'Aktif',

            // Licenses
            'license_name' => $app['licenses'] ?? '-',
            'license_provider' => $isDown ? '-' : 'Microsoft',
            'license_count' => $isDown ? '-' : '2 License',
            'license_expired' => $isDown ? '-' : '20 Sep 2026',
            'license_expired_note' => $isDown ? '-' : '(38 Hari Lagi)',
            'license_status' => $isDown ? 'Expired' : 'Akan Expired',

            // Pengurus / PIC
            'pic_name' => 'Fathier Assyarief',
            'pic_jabatan' => 'Data Analyst',
            'pic_email' => 'Fathier.assyarief@gmail.com',
            'pic_telepon' => '+62 813 8306 5203',
            'pic_divisi' => 'IT Infrastructure',

            // Maintenance Terakhir
            'last_maintenance' => $isDown ? null : [
                'tanggal' => '20 Agustus 2026',
                'jenis' => 'Update & Patch',
                'pic' => 'Chairul Leclerc',
                'status' => 'Completed',
            ],

            // Maintenance Berikutnya
            'next_maintenance' => $isDown ? null : [
                'tanggal' => '12 September 2026',
                'jenis' => 'Upgrade RAM',
                'pic' => 'Lauren Makies',
                'status' => 'Scheduled',
            ],

            // Riwayat Maintenance
            'maintenance_history' => $isDown ? [] : [
                ['tanggal' => '20 Agustus 2026', 'jenis' => 'Update & Patch', 'status' => 'Completed'],
                ['tanggal' => '12 Juli 2026', 'jenis' => 'Upgrade SSD', 'status' => 'Completed'],
                ['tanggal' => '17 Juni 2026', 'jenis' => 'Fix Bug', 'status' => 'Completed'],
                ['tanggal' => '4 Juni 2026', 'jenis' => 'Fix Data Leaks', 'status' => 'Completed'],
                ['tanggal' => '12 September 2026', 'jenis' => 'Upgrade RAM', 'status' => 'Scheduled'],
                ['tanggal' => '20 Juni 2026', 'jenis' => 'Cleaning Storage', 'status' => 'Canceled'],
            ],
        ];
    }

    /**
     * ===== SUMBER DATA UTAMA APLIKASI =====
     */
    private function applications(): array
    {
        return [
            ['id' => 1, 'name' => 'GTK - Guru', 'server_name' => 'SVR-001', 'status' => 'Aktif', 'domain' => 'webGTK.com', 'licenses' => 'Microsoft SQL Server', 'next_maintenance' => '20 Agustus 2026'],
            ['id' => 2, 'name' => 'GTK - Pendidikan', 'server_name' => 'SVR-001', 'status' => 'Aktif', 'domain' => 'webGTK.com', 'licenses' => 'Django', 'next_maintenance' => '20 Agustus 2026'],
            ['id' => 3, 'name' => 'GTK - TKA', 'server_name' => 'SVR-001', 'status' => 'Down', 'domain' => 'webGTK.com', 'licenses' => 'Microsoft SQL Server', 'next_maintenance' => '20 Agustus 2026'],
            ['id' => 4, 'name' => 'GTK - PPPK', 'server_name' => 'SVR-002', 'status' => 'Aktif', 'domain' => 'webGTK.com', 'licenses' => 'Microsoft 365', 'next_maintenance' => '20 September 2026'],
            ['id' => 5, 'name' => 'GTK - Mutasi', 'server_name' => 'SVR-005', 'status' => 'Aktif', 'domain' => 'webGTK.com', 'licenses' => 'Oracle', 'next_maintenance' => '15 September 2026'],
            ['id' => 6, 'name' => 'GTK - SIMPKB', 'server_name' => 'SVR-001', 'status' => 'Aktif', 'domain' => 'webGTK.com', 'licenses' => 'VMware', 'next_maintenance' => '20 Agustus 2026'],
            ['id' => 7, 'name' => 'GTK - Ujian', 'server_name' => 'SVR-003', 'status' => 'Aktif', 'domain' => 'webGTK.com', 'licenses' => 'Adobe', 'next_maintenance' => '10 Oktober 2026'],
            ['id' => 8, 'name' => 'GTK - Sertifikasi', 'server_name' => 'SVR-004', 'status' => 'Down', 'domain' => 'webGTK.com', 'licenses' => 'Microsoft SQL Server', 'next_maintenance' => '20 Agustus 2026'],
        ];
    }
}