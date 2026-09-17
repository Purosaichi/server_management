<?php

namespace App\Http\Controllers;

class DomainController extends Controller
{
    /**
     * Halaman daftar domain
     */
    public function index()
    {
        $domains = $this->domains();

        return view('pages.domain.index', compact('domains'));
    }

    /**
     * Halaman detail domain
     */
    public function show(int $id)
    {
        // Ambil data dari sumber utama
        $domain = collect($this->domains())->firstWhere('id', $id);

        abort_unless($domain, 404);

        // Gabungin dengan data detail
        $domain = array_merge($domain, $this->domainDetails($domain));

        return view('pages.domain.detail', compact('domain'));
    }

    /**
     * Data detail domain (ngikut data utama)
     */
    private function domainDetails(array $domain): array
    {
        $isInactive = $domain['status'] === 'Tidak Aktif';

        return [
            // Info Utama
            'purchase_date' => '13 Januari 2024',
            'expiration_date' => '19 November 2028',
            'auto_renewal' => $isInactive ? 'Inactive' : 'Active',
            'domain_status' => $isInactive ? 'Inactive' : 'Normal',

            // PIC
            'pic_name' => 'Fathier Assyarief',
            'pic_jabatan' => 'IT Infrastructure',
            'pic_email' => 'Fathier.assyarief@gmail.com',
            'pic_telepon' => '+62 813 8306 5203',

            // SSL Certificate
            'ssl_provider' => $isInactive ? '-' : "Let's Encrypt",
            'ssl_status' => $isInactive ? 'Invalid' : 'Valid',
            'ssl_issued_date' => $isInactive ? '-' : '16 Agustus 2026',
            'ssl_expiration_date' => $isInactive ? '-' : '19 November 2028',
            'ssl_certificate_type' => $isInactive ? '-' : 'Domain Validation (DV)',

            // Billing Information
            'billing_status' => $isInactive ? 'Unpaid' : 'Paid',
            'billing_cycle' => '1 Tahun',
            'last_payment' => '15 Agustus 2026',
            'next_due_date' => '19 November 2028',
            'amount' => '250.000',
            'payment_method' => 'Transfer bank',

            // Upcoming Reminders
            'reminders' => $isInactive ? [] : [
                [
                    'type' => 'warning',
                    'title' => 'Domain akan expired',
                    'description' => $domain['domain'] . ' akan expired dalam 21 hari',
                    'date' => '19 Nov 2028',
                ],
                [
                    'type' => 'info',
                    'title' => 'SSL Certificate akan expired',
                    'description' => 'Sertifikat SSL akan expired dalam 40 hari',
                    'date' => '19 Nov 2028',
                ],
                [
                    'type' => 'info',
                    'title' => 'Perpanjang Domain',
                    'description' => 'Jadwalkan perpanjangan domain berikutnya',
                    'date' => '19 Nov 2028',
                ],
            ],

            // Renewal / Activity History
            'activity_history' => $isInactive ? [] : [
                [
                    'tanggal' => '11 Agustus 2026',
                    'aktifitas' => 'Domain registration',
                    'pic' => 'Fathier assyarief',
                    'status' => 'Completed',
                    'catatan' => 'Registrasi domain pertama',
                ],
            ],
        ];
    }

    /**
     * ===== SUMBER DATA UTAMA DOMAIN =====
     */
    private function domains(): array
    {
        return [
            ['id' => 1, 'domain' => 'WebGTK-01.com', 'application' => 'App GTK 01', 'status' => 'Aktif', 'pic' => 'Fathier Assyarief', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
            ['id' => 2, 'domain' => 'WebGTK-02.com', 'application' => 'App GTK 02', 'status' => 'Aktif', 'pic' => 'Fathier Assyarief', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
            ['id' => 3, 'domain' => 'WebGTK-03.com', 'application' => 'App GTK 03', 'status' => 'Aktif', 'pic' => 'Fathier Assyarief', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
            ['id' => 4, 'domain' => 'WebGTK-04.com', 'application' => 'App GTK 04', 'status' => 'Aktif', 'pic' => 'Fathier Assyarief', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
            ['id' => 5, 'domain' => 'WebGTK-05.com', 'application' => 'App GTK 05', 'status' => 'Tidak Aktif', 'pic' => 'Fathier Assyarief', 'ssl' => 'Tidak Aktif', 'last_activity' => '20 Agustus 2026'],
            ['id' => 6, 'domain' => 'WebGTK-06.com', 'application' => 'App GTK 06', 'status' => 'Aktif', 'pic' => 'Fathier Assyarief', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
            ['id' => 7, 'domain' => 'WebGTK-07.com', 'application' => 'App GTK 07', 'status' => 'Aktif', 'pic' => 'Fathier Assyarief', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
            ['id' => 8, 'domain' => 'WebGTK-08.com', 'application' => 'App GTK 08', 'status' => 'Aktif', 'pic' => 'Fathier Assyarief', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
            ['id' => 9, 'domain' => 'WebGTK-09.com', 'application' => 'App GTK 09', 'status' => 'Aktif', 'pic' => 'Fathier Assyarief', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
        ];
    }
}