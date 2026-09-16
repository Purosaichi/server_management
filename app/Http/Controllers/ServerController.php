<?php

namespace App\Http\Controllers;

class ServerController extends Controller
{
    /**
     * Halaman daftar server
     */
    public function index()
    {
        $servers = $this->servers();

        return view('pages.server.server', compact('servers'));
    }

    /**
     * Halaman detail server
     * Data diambil dari method servers() + tambahan detail
     */
    public function show(int $id)
    {
        // Ambil data server dari sumber yang sama dengan index
        $server = collect($this->servers())->firstWhere('id', $id);

        abort_unless($server, 404);

        // Tambahin data detail (data teknis)
        $server = array_merge($server, $this->serverDetails($server));

        return view('pages.server.detail', compact('server'));
    }

    /**
     * Data detail server (data teknis)
     * Data ini ngikut data server utama
     */
    private function serverDetails(array $server): array
    {
        // Kalo server offline, data teknis dikosongin
        $isOffline = $server['status'] === 'Offline';

        return [
            'ip' => $server['ip_address'] ?? $server['ip'] ?? '-',
            'ip_address' => $server['ip_address'] ?? $server['ip'] ?? '-',
            'subtitle' => 'Server Utama Aplikasi & Database',
            'hostname' => strtolower($server['name']) . '.kemendik.local',
            'location' => 'Data Center, Rack A0' . $server['id'],
            'uptime_since' => $isOffline ? '-' : '15 Juli 2026, 12:00',
            'os' => $isOffline ? '-' : 'Windows Server 2025 Standard',
            'status_monitoring' => $isOffline ? 'Tidak Aktif' : 'Aktif',
            'last_check' => $isOffline ? '-' : '13 Agustus 2026, 09:20:15',
            'last_check_note' => $isOffline ? '-' : '(2 Hari yang lalu)',

            // Resource Utilization (kalo offline, 0%)
            'cpu_percent' => $isOffline ? 0 : (int) str_replace('%', '', $server['cpu']),
            'cpu_detail' => $isOffline ? '-' : '2 Core (4.2 GHz)',
            'memory_percent' => $isOffline ? 0 : (int) str_replace('%', '', $server['ram']),
            'memory_detail' => $isOffline ? '-' : '98 GB / 128 GB',
            'storage_percent' => $isOffline ? 0 : (int) str_replace('%', '', $server['disk']),
            'storage_detail' => $isOffline ? '-' : '85 TB / 100 TB',

            // Informasi Dasar
            'server_type' => 'Physical',
            'server_role' => 'Application & Database Server',
            'manufacture' => $isOffline ? '-' : 'Dell Inc.',
            'model' => $isOffline ? '-' : 'PowerEdge R7625',
            'serial_number' => $isOffline ? '-' : 'DELLR7625-8F3K' . $server['id'],
            'purchase_date' => '15 Januari 2025',
            'warranty' => 'Hingga 15 Januari 2028',
            'server_status' => $server['status'],

            // Spesifikasi Hardware
            'cpu_spec' => $isOffline ? '-' : 'AMD EPYC 9655 (96 Core/192 Thread)',
            'ram_spec' => $isOffline ? '-' : '1024 GB DDR5 ECC RDIMM',
            'storage_spec' => $isOffline ? '-' : '10 TB NVMe Enterprise U.2/U.3',

            // Informasi Jaringan
            'subnet_mask' => '255.255.255.0',
            'gateway' => '192.168.1.1',
            'dns_server' => '8.8.8.8, 1.1.1.1',
            'mac_address' => $isOffline ? '-' : '00:1A:2B:3C:4D:5' . $server['id'],
            'speed' => $isOffline ? '-' : '1 Gbps',
            'network_usage_down' => $isOffline ? '-' : '125 Mbps',
            'network_usage_up' => $isOffline ? '-' : '48 Mbps',

            // Catatan Terakhir
            'last_note' => $isOffline ? 'Server sedang offline' : 'Disk sudah mulai penuh',
            'last_note_by' => 'Fathier Assyarief',
            'last_note_date' => $isOffline ? '-' : '12 Agustus 2026, 12:12',

            // Riwayat Maintenance
            'maintenance_history' => $isOffline ? [] : [
                [
                    'tanggal' => '12 Agustus 2026, 12:12',
                    'jenis' => 'Update & Patch',
                    'pic' => 'Fathier Assyarief',
                    'status' => 'Completed'
                ],
            ],
        ];
    }

    /**
     * ===== SUMBER DATA UTAMA SERVER =====
     * Data ini dipake di index() dan show()
     */
    private function servers(): array
    {
        return [
            ['id' => 1, 'name' => 'SVR-001', 'ip' => '103.231.3.01', 'ip_address' => '103.231.3.01', 'status' => 'Online', 'cpu' => '60%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
            ['id' => 2, 'name' => 'SVR-002', 'ip' => '103.231.3.02', 'ip_address' => '103.231.3.02', 'status' => 'Online', 'cpu' => '45%', 'ram' => '65%', 'disk' => '85%', 'uptime' => '15d 8h'],
            ['id' => 3, 'name' => 'SVR-003', 'ip' => '103.231.3.03', 'ip_address' => '103.231.3.03', 'status' => 'Online', 'cpu' => '80%', 'ram' => '90%', 'disk' => '70%', 'uptime' => '5d 3h'],
            ['id' => 4, 'name' => 'SVR-004', 'ip' => '103.231.3.04', 'ip_address' => '103.231.3.04', 'status' => 'Offline', 'cpu' => '-', 'ram' => '-', 'disk' => '-', 'uptime' => '-'],
            ['id' => 5, 'name' => 'SVR-005', 'ip' => '103.231.3.05', 'ip_address' => '103.231.3.05', 'status' => 'Offline', 'cpu' => '60%', 'ram' => '75%', 'disk' => '80%', 'uptime' => '30d 2h'],
            ['id' => 6, 'name' => 'SVR-006', 'ip' => '103.231.3.06', 'ip_address' => '103.231.3.06', 'status' => 'Online', 'cpu' => '55%', 'ram' => '60%', 'disk' => '75%', 'uptime' => '10d 5h'],
            ['id' => 7, 'name' => 'SVR-007', 'ip' => '103.231.3.07', 'ip_address' => '103.231.3.07', 'status' => 'Online', 'cpu' => '40%', 'ram' => '50%', 'disk' => '60%', 'uptime' => '25d 18h'],
            ['id' => 8, 'name' => 'SVR-008', 'ip' => '103.231.3.08', 'ip_address' => '103.231.3.08', 'status' => 'Online', 'cpu' => '-', 'ram' => '-', 'disk' => '-', 'uptime' => '-'],
            ['id' => 9, 'name' => 'SVR-009', 'ip' => '103.231.3.09', 'ip_address' => '103.231.3.09', 'status' => 'Online', 'cpu' => '70%', 'ram' => '80%', 'disk' => '85%', 'uptime' => '8d 4h'],
            ['id' => 10, 'name' => 'SVR-010', 'ip' => '103.231.3.10', 'ip_address' => '103.231.3.10', 'status' => 'Online', 'cpu' => '30%', 'ram' => '40%', 'disk' => '50%', 'uptime' => '40d 10h'],
            ['id' => 11, 'name' => 'SVR-011', 'ip' => '103.231.3.11', 'ip_address' => '103.231.3.11', 'status' => 'Offline', 'cpu' => '-', 'ram' => '-', 'disk' => '-', 'uptime' => '-'],
            ['id' => 12, 'name' => 'SVR-012', 'ip' => '103.231.3.12', 'ip_address' => '103.231.3.12', 'status' => 'Online', 'cpu' => '65%', 'ram' => '70%', 'disk' => '88%', 'uptime' => '12d 6h'],
            ['id' => 13, 'name' => 'SVR-013', 'ip' => '103.231.3.13', 'ip_address' => '103.231.3.13', 'status' => 'Online', 'cpu' => '45%', 'ram' => '55%', 'disk' => '65%', 'uptime' => '18d 14h'],
            ['id' => 14, 'name' => 'SVR-014', 'ip' => '103.231.3.14', 'ip_address' => '103.231.3.14', 'status' => 'Online', 'cpu' => '35%', 'ram' => '45%', 'disk' => '55%', 'uptime' => '22d 20h'],
        ];
    }
}