<?php

namespace App\Http\Controllers;

class LicenseController extends Controller
{
    public function index()
    {
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
                'start_date' => '14 Agustus 2025',
                'last_payment' => '14 Agustus 2025',
                'next_due_date' => '20 September 2026',
                'amount' => '267.000',
                'payment_method' => 'Transfer bank',
                'license_number' => 'MSQL-STD-2025-ABCD1234',
                'license_type' => 'Subscription',
                'asset_name' => 'Server Database Utama',
                'hostname' => 'DB-PROD-01',
                'ip_address' => '10.10.10.5',
                'location' => 'Data Center Jakarta',
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
                'start_date' => '15 Maret 2026',
                'last_payment' => '15 Maret 2026',
                'next_due_date' => '15 Maret 2027',
                'amount' => '150.000',
                'payment_method' => 'Transfer bank',
                'license_number' => 'DJANGO-OPEN-SOURCE',
                'license_type' => 'Open source',
                'asset_name' => 'Application Platform',
                'hostname' => 'APP-PROD-01',
                'ip_address' => '10.10.20.10',
                'location' => 'Data Center Jakarta',
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
                'start_date' => '26 Mei 2026',
                'last_payment' => '26 Mei 2026',
                'next_due_date' => '26 Mei 2027',
                'amount' => '1.200.000',
                'payment_method' => 'Transfer bank',
                'license_number' => 'M365-BUSINESS-2026',
                'license_type' => 'Subscription',
                'asset_name' => 'Office User Accounts',
                'hostname' => 'M365-TENANT',
                'ip_address' => 'Cloud service',
                'location' => 'Microsoft Cloud',
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
                'start_date' => '20 Agustus 2025',
                'last_payment' => '20 Agustus 2025',
                'next_due_date' => '20 Agustus 2026',
                'amount' => '8.500.000',
                'payment_method' => 'Transfer bank',
                'license_number' => 'VMW-VSF-2025-001',
                'license_type' => 'Subscription',
                'asset_name' => 'Virtualization Cluster',
                'hostname' => 'VMWARE-CLUSTER-01',
                'ip_address' => '10.10.30.10',
                'location' => 'Data Center Jakarta',
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
                'start_date' => '7 Oktober 2025',
                'last_payment' => '7 Oktober 2025',
                'next_due_date' => '7 Oktober 2026',
                'amount' => '12.000.000',
                'payment_method' => 'Transfer bank',
                'license_number' => 'ORA-DB-ENT-2025-001',
                'license_type' => 'Subscription',
                'asset_name' => 'Enterprise Database Cluster',
                'hostname' => 'ORA-PROD-01',
                'ip_address' => '10.10.40.10',
                'location' => 'Data Center Jakarta',
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
                'start_date' => '30 Juni 2026',
                'last_payment' => '30 Juni 2026',
                'next_due_date' => '30 Juni 2027',
                'amount' => '4.000.000',
                'payment_method' => 'Transfer bank',
                'license_number' => 'ADOBE-ACROBAT-2026',
                'license_type' => 'Subscription',
                'asset_name' => 'Document Management Users',
                'hostname' => 'ADOBE-ADMIN',
                'ip_address' => 'Cloud service',
                'location' => 'Adobe Cloud',
            ],
        ];

        abort_unless(isset($licenses[(int) $id]), 404);

        $license = array_merge(['id' => (int) $id], $licenses[(int) $id]);

        return view('pages.licenses.detail', compact('license'));
    }
}