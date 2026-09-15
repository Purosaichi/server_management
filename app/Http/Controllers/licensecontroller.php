<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index()
    {
        // ===== DATA LISENSI =====
        $licenses = [
            [
                'logo' => 'SWL_server.png',
                'name' => 'Microsoft SQL Server Standard',
                'provider' => 'Microsoft',
                'status' => 'Akan Expired',
                'status_type' => 'warning',
                'jumlah' => '2 License',
                'expired' => '20 Sep 2026',
                'expired_note' => '(38 Hari Lagi)',
                'expired_type' => 'warning'
            ],
            [
                'logo' => 'DJango.png',
                'name' => 'Django',
                'provider' => 'Django Software Foundation',
                'status' => 'Aman',
                'status_type' => 'safe',
                'jumlah' => '4 License',
                'expired' => '15 Maret 2027',
                'expired_note' => '',
                'expired_type' => 'safe'
            ],
            [
                'logo' => 'microsoft.png',
                'name' => 'Microsoft 365',
                'provider' => 'Microsoft',
                'status' => 'Aman',
                'status_type' => 'safe',
                'jumlah' => '7 License',
                'expired' => '26 Mei 2027',
                'expired_note' => '',
                'expired_type' => 'safe'
            ],
            [
                'logo' => 'VMware.png',
                'name' => 'VMware vSphere Enterprise Plus',
                'provider' => 'VMware vSphere Foundation',
                'status' => 'Expired',
                'status_type' => 'expired',
                'jumlah' => '3 License',
                'expired' => '20 Agustus 2026',
                'expired_note' => '(Segera Bayar)',
                'expired_type' => 'expired'
            ],
            [
                'logo' => 'oracle.png',
                'name' => 'Oracle Database Enterprise Edition',
                'provider' => 'Oracle',
                'status' => 'Akan Expired',
                'status_type' => 'warning',
                'jumlah' => '10 License',
                'expired' => '7 Oktober 2026',
                'expired_note' => '(48 Hari Lagi)',
                'expired_type' => 'warning'
            ],
            [
                'logo' => 'adobe.png',
                'name' => 'Adobe Acrobat Pro',
                'provider' => 'Adobe',
                'status' => 'Aman',
                'status_type' => 'safe',
                'jumlah' => '8 License',
                'expired' => '30 Juni 2027',
                'expired_note' => '',
                'expired_type' => 'safe'
            ],
        ];

        return view('pages.licenses.licenses', compact('licenses'));
    }

    public function show($id)
    {
        $licenses = [
            1 => [
                'logo' => 'SWL_server.png',
                'name' => 'Microsoft SQL Server Standard',
                'provider' => 'Microsoft',
                'status' => 'Akan Expired',
                'jumlah' => '2 License',
                'expired' => '20 Sep 2026 (38 Hari Lagi)',
                'pic' => 'Fathier Assyarief',
                'digunakan_oleh' => 'SVR-001, SVR-002',
                'keterangan' => 'Lisensi digunakan untuk database server utama.',
            ],
            2 => [
                'logo' => 'DJango.png',
                'name' => 'Django',
                'provider' => 'Django Software Foundation',
                'status' => 'Aman',
                'jumlah' => '4 License',
                'expired' => '15 Maret 2027',
                'pic' => 'Fathier Assyarief',
                'digunakan_oleh' => 'APP-001, APP-002',
                'keterangan' => 'Framework aplikasi yang digunakan untuk pengembangan sistem.',
            ],
            3 => [
                'logo' => 'microsoft.png',
                'name' => 'Microsoft 365',
                'provider' => 'Microsoft',
                'status' => 'Aman',
                'jumlah' => '7 License',
                'expired' => '26 Mei 2027',
                'pic' => 'Fathier Assyarief',
                'digunakan_oleh' => 'USR-001 sampai USR-007',
                'keterangan' => 'Lisensi produktivitas untuk kebutuhan operasional kantor.',
            ],
            4 => [
                'logo' => 'VMware.png',
                'name' => 'VMware vSphere Enterprise Plus',
                'provider' => 'VMware vSphere Foundation',
                'status' => 'Expired',
                'jumlah' => '3 License',
                'expired' => '20 Agustus 2026 (Segera Bayar)',
                'pic' => 'Fathier Assyarief',
                'digunakan_oleh' => 'SVR-001 sampai SVR-003',
                'keterangan' => 'Lisensi virtualisasi untuk infrastruktur server.',
            ],
            5 => [
                'logo' => 'oracle.png',
                'name' => 'Oracle Database Enterprise Edition',
                'provider' => 'Oracle',
                'status' => 'Akan Expired',
                'jumlah' => '10 License',
                'expired' => '7 Oktober 2026 (48 Hari Lagi)',
                'pic' => 'Fathier Assyarief',
                'digunakan_oleh' => 'DB-001 sampai DB-010',
                'keterangan' => 'Lisensi database untuk aplikasi layanan utama.',
            ],
            6 => [
                'logo' => 'adobe.png',
                'name' => 'Adobe Acrobat Pro',
                'provider' => 'Adobe',
                'status' => 'Aman',
                'jumlah' => '8 License',
                'expired' => '30 Juni 2027',
                'pic' => 'Fathier Assyarief',
                'digunakan_oleh' => 'USR-008 sampai USR-015',
                'keterangan' => 'Lisensi pengolahan dokumen PDF untuk pengguna terkait.',
            ],
        ];

        abort_unless(isset($licenses[(int) $id]), 404);

        $license = array_merge(['id' => (int) $id], $licenses[(int) $id]);

        return view('pages.licenses.detail', compact('license'));
    }
}