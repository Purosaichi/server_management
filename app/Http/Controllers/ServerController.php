<?php

namespace App\Http\Controllers;

class ServerController extends Controller
{
    public function index()
    {
        $servers = $this->servers();

        return view('pages.server.server', compact('servers'));
    }

    public function show(int $id)
    {
        $server = collect($this->servers())->firstWhere('id', $id);

        abort_unless($server, 404);

        $server += [
            'ip_address' => $server['ip'] ?? '127.0.0.1',
            'subtitle' => 'Server Utama Aplikasi & Database',
            'hostname' => 'SVR-00' . $id . '.kemendik.local',
            'location' => 'Data Center, Rack A01',
            'uptime_since' => '15 Juli 2026, 12:00',
            'os' => 'Windows Server 2025 Standard',
            'status_monitoring' => 'Nonaktif',
            'last_check' => '13 Agustus 2026, 09:20:15',
            'last_check_note' => '(2 Hari yang lalu)',
            
            // Resource Utilization
            'cpu_percent' => 54,
            'cpu_detail' => '2 Core (4.2 GHz)',
            'memory_percent' => 70,
            'memory_detail' => '98 GB / 128 GB',
            'storage_percent' => 98,
            'storage_detail' => '85 TB / 100 TB',
            
            // Informasi Dasar
            'server_type' => 'Physical',
            'server_role' => 'Application & Database Server',
            'manufacture' => 'Dell Inc.',
            'model' => 'PowerEdge R7625',
            'serial_number' => 'DELLR7625-8F3K29',
            'purchase_date' => '15 Januari 2025',
            'warranty' => 'Hingga 15 Januari 2028',
            'server_status' => 'Normal',
            
            // Spesifikasi Hardware
            'cpu_spec' => 'AMD EPYC 9655 (96 Core/192 Thread)',
            'ram_spec' => '1024 GB DDR5 ECC RDIMM',
            'storage_spec' => '10 TB NVMe Enterprise U.2/U.3',
            
            // Informasi Jaringan
            'subnet_mask' => '255.255.255.0',
            'gateway' => '192.168.1.1',
            'dns_server' => '8.8.8.8, 1.1.1.1',
            'mac_address' => '00:1A:2B:3C:4D:5E',
            'speed' => '1 Gbps',
            'network_usage_down' => '125 Mbps',
            'network_usage_up' => '48 Mbps',
            
            // Catatan Terakhir
            'last_note' => 'Disk sudah mulai penuh',
            'last_note_by' => 'Fathier Assyarief',
            'last_note_date' => '12 Agustus 2026, 12:12',
            
            // Riwayat Maintenance
            'maintenance_history' => [
                [
                    'tanggal' => '12 Agustus 2026, 12:12',
                    'jenis' => 'Update & Patch',
                    'pic' => 'Fathier Assyarief',
                    'status' => 'Completed'
                ],
                [
                    'tanggal' => '14 September 2026, 12:12',
                    'jenis' => 'Update & Patch',
                    'pic' => 'Fathier Assyarief',
                    'status' => 'Completed'
                ],
                [
                    'tanggal' => '12 Oktober 2026, 12:12',
                    'jenis' => 'Update & Patch',
                    'pic' => 'Fathier Assyarief',
                    'status' => 'Completed'
                ],
            ],
        ];

        return view('pages.server.detail', compact('server'));
    }

    /**
     * Data hardcode semua server
     */
    private function servers(): array
    {
        return [
            ['id' => 1, 'name' => 'SVR-001', 'ip' => '103.231.3.01', 'status' => 'Offline', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
            ['id' => 2, 'name' => 'SVR-002', 'ip' => '103.231.3.02', 'status' => 'Online', 'cpu' => '45%', 'ram' => '65%', 'disk' => '85%', 'uptime' => '15d 8h'],
            ['id' => 3, 'name' => 'SVR-003', 'ip' => '103.231.3.03', 'status' => 'Online', 'cpu' => '80%', 'ram' => '90%', 'disk' => '70%', 'uptime' => '5d 3h'],
            ['id' => 4, 'name' => 'SVR-004', 'ip' => '103.231.3.04', 'status' => 'Offline', 'cpu' => '-', 'ram' => '-', 'disk' => '-', 'uptime' => '-'],
            ['id' => 5, 'name' => 'SVR-005', 'ip' => '103.231.3.05', 'status' => 'Online', 'cpu' => '60%', 'ram' => '75%', 'disk' => '80%', 'uptime' => '30d 2h'],
            ['id' => 6, 'name' => 'SVR-006', 'ip' => '103.231.3.06', 'status' => 'Online', 'cpu' => '55%', 'ram' => '60%', 'disk' => '75%', 'uptime' => '10d 5h'],
            ['id' => 7, 'name' => 'SVR-007', 'ip' => '103.231.3.07', 'status' => 'Online', 'cpu' => '40%', 'ram' => '50%', 'disk' => '60%', 'uptime' => '25d 18h'],
            ['id' => 8, 'name' => 'SVR-008', 'ip' => '103.231.3.08', 'status' => 'Offline', 'cpu' => '-', 'ram' => '-', 'disk' => '-', 'uptime' => '-'],
            ['id' => 9, 'name' => 'SVR-009', 'ip' => '103.231.3.09', 'status' => 'Online', 'cpu' => '70%', 'ram' => '80%', 'disk' => '85%', 'uptime' => '8d 4h'],
            ['id' => 10, 'name' => 'SVR-010', 'ip' => '103.231.3.10', 'status' => 'Online', 'cpu' => '30%', 'ram' => '40%', 'disk' => '50%', 'uptime' => '40d 10h'],
            ['id' => 11, 'name' => 'SVR-011', 'ip' => '103.231.3.11', 'status' => 'Offline', 'cpu' => '-', 'ram' => '-', 'disk' => '-', 'uptime' => '-'],
            ['id' => 12, 'name' => 'SVR-012', 'ip' => '103.231.3.12', 'status' => 'Online', 'cpu' => '65%', 'ram' => '70%', 'disk' => '88%', 'uptime' => '12d 6h'],
            ['id' => 13, 'name' => 'SVR-013', 'ip' => '103.231.3.13', 'status' => 'Online', 'cpu' => '45%', 'ram' => '55%', 'disk' => '65%', 'uptime' => '18d 14h'],
            ['id' => 14, 'name' => 'SVR-014', 'ip' => '103.231.3.14', 'status' => 'Online', 'cpu' => '35%', 'ram' => '45%', 'disk' => '55%', 'uptime' => '22d 20h'],
        ];
    }
}