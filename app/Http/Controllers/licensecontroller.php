<?php

namespace App\Http\Controllers;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = $this->licenses();

        return view('pages.licenses.licenses', compact('licenses'));
    }

    public function show(int $id)
    {
        $license = collect($this->licenses())->firstWhere('id', $id);

        abort_unless($license, 404);

        // Tambahin data detail
        $license = array_merge($license, [
            'start_date' => '01 Januari 2026',
            'license_number' => 'LIC-' . strtoupper(substr(md5($license['name']), 0, 8)),
            'license_type' => $license['status'] === 'Aman' ? 'Perpetual' : 'Subscription',
            'last_payment' => '15 Agustus 2026',
            'next_due_date' => $license['expired'],
            'amount' => '250.000',
            'payment_method' => 'Transfer bank',
            'asset_name' => 'SVR-001',
            'hostname' => 'svr-001.kemendik.local',
            'ip_address' => '103.231.3.01',
            'location' => 'Data Center, Rack A01',
            'pic' => 'Fathier Assyarief',
        ]);

        return view('pages.licenses.detail', compact('license'));
    }

    private function licenses(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Microsoft SQL Server',
                'provider' => 'Microsoft',
                'status' => 'Akan Expired',
                'status_type' => 'warning',
                'jumlah' => '2 License',
                'expired' => '20 Sep 2026',
                'expired_note' => '(38 Hari Lagi)',
                'expired_type' => 'warning',
                'logo' => 'SWL_server.png',
            ],
            [
                'id' => 2,
                'name' => 'Django',
                'provider' => 'Django Software Foundation',
                'status' => 'Aman',
                'status_type' => 'safe',
                'jumlah' => '4 License',
                'expired' => '15 Maret 2027',
                'expired_note' => '',
                'expired_type' => 'safe',
                'logo' => 'DJango.png',
            ],
            [
                'id' => 3,
                'name' => 'Microsoft 365',
                'provider' => 'Microsoft',
                'status' => 'Aman',
                'status_type' => 'safe',
                'jumlah' => '7 License',
                'expired' => '26 Mei 2027',
                'expired_note' => '',
                'expired_type' => 'safe',
                'logo' => 'microsoft.png',
            ],
            [
                'id' => 4,
                'name' => 'VMware vSphere Enterprise Plus',
                'provider' => 'VMware vSphere Foundation',
                'status' => 'Expired',
                'status_type' => 'expired',
                'jumlah' => '3 License',
                'expired' => '20 Agustus 2026',
                'expired_note' => '(Segera Bayar)',
                'expired_type' => 'expired',
                'logo' => 'VMware.png',
            ],
            [
                'id' => 5,
                'name' => 'Oracle Database Enterprise Edition',
                'provider' => 'Oracle',
                'status' => 'Akan Expired',
                'status_type' => 'warning',
                'jumlah' => '10 License',
                'expired' => '7 Oktober 2026',
                'expired_note' => '(48 Hari Lagi)',
                'expired_type' => 'warning',
                'logo' => 'oracle.png',
            ],
            [
                'id' => 6,
                'name' => 'Adobe Acrobat Pro',
                'provider' => 'Adobe',
                'status' => 'Aman',
                'status_type' => 'safe',
                'jumlah' => '8 License',
                'expired' => '30 Juni 2027',
                'expired_note' => '',
                'expired_type' => 'safe',
                'logo' => 'adobe.png',
            ],
        ];
    }
}