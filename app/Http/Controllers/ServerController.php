<?php

namespace App\Http\Controllers;

use App\Models\ServerStatus;

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

        $server = array_merge($server, $this->serverDetails($server));

        return view('pages.server.detail', compact('server'));
    }

    private function serverDetails(array $server): array
    {
        $isOffline = $server['status'] === 'Offline';

        return [
            'ip_address' => $server['ip_address'] ?? '-',
            'subtitle' => 'Server Utama Aplikasi & Database',
            'hostname' => strtolower($server['name']) . '.kemendik.local',
            'location' => 'Data Center, Rack A0' . $server['id'],
            'uptime_since' => $isOffline ? '-' : '15 Juli 2026, 12:00',
            'os' => $isOffline ? '-' : 'Windows Server 2025 Standard',
            'status_monitoring' => $isOffline ? 'Tidak Aktif' : 'Aktif',
            'last_check' => $isOffline ? '-' : '13 Agustus 2026, 09:20:15',
            'last_check_note' => $isOffline ? '-' : '(2 Hari yang lalu)',

            'cpu_percent' => $isOffline ? 0 : (int) str_replace('%', '', $server['cpu']),
            'cpu_detail' => $isOffline ? '-' : '2 Core (4.2 GHz)',
            'memory_percent' => $isOffline ? 0 : (int) str_replace('%', '', $server['ram']),
            'memory_detail' => $isOffline ? '-' : '98 GB / 128 GB',
            'storage_percent' => $isOffline ? 0 : (int) str_replace('%', '', $server['disk']),
            'storage_detail' => $isOffline ? '-' : '85 TB / 100 TB',

            'server_type' => 'Physical',
            'server_role' => 'Application & Database Server',
            'manufacture' => $isOffline ? '-' : 'Dell Inc.',
            'model' => $isOffline ? '-' : 'PowerEdge R7625',
            'serial_number' => $isOffline ? '-' : 'DELLR7625-8F3K' . $server['id'],
            'purchase_date' => '15 Januari 2025',
            'warranty' => 'Hingga 15 Januari 2028',
            'server_status' => $server['status'],

            'cpu_spec' => $isOffline ? '-' : 'AMD EPYC 9655 (96 Core/192 Thread)',
            'ram_spec' => $isOffline ? '-' : '1024 GB DDR5 ECC RDIMM',
            'storage_spec' => $isOffline ? '-' : '10 TB NVMe Enterprise U.2/U.3',

            'subnet_mask' => '255.255.255.0',
            'gateway' => '192.168.1.1',
            'dns_server' => '8.8.8.8, 1.1.1.1',
            'mac_address' => $isOffline ? '-' : '00:1A:2B:3C:4D:5' . $server['id'],
            'speed' => $isOffline ? '-' : '1 Gbps',
            'network_usage_down' => $isOffline ? '-' : '125 Mbps',
            'network_usage_up' => $isOffline ? '-' : '48 Mbps',

            'last_note' => $isOffline ? 'Server sedang offline' : 'Disk sudah mulai penuh',
            'last_note_by' => 'Fathier Assyarief',
            'last_note_date' => $isOffline ? '-' : '12 Agustus 2026, 12:12',

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

    private function servers(): array
    {
        return ServerStatus::query()
            ->orderBy('id_server')
            ->get()
            ->map(function ($server) {
                $status = $server->status ?? 'Offline';
                $cpu = $server->cpu_percent ? (string) $server->cpu_percent . '%' : '-';
                $ram = $server->memory_percent ? (string) $server->memory_percent . '%' : '-';
                $disk = $server->storage_percent ? (string) $server->storage_percent . '%' : '-';
                $uptime = $this->formatUptime($server->uptime_detik ?? null);

                return [
                    'id' => (int) $server->id_server,
                    'name' => $server->nama_server ?? '-',
                    'ip_address' => $server->alamat_ip_produksi ?? '-',
                    'status' => $status,
                    'cpu' => $cpu,
                    'ram' => $ram,
                    'disk' => $disk,
                    'uptime' => $uptime,
                ];
            })
            ->toArray();
    }

    private function formatUptime($detik): string
    {
        if (!$detik) {
            return '-';
        }

        $hari = (int) floor($detik / 86400);
        $jam = (int) floor(($detik % 86400) / 3600);
        $menit = (int) floor(($detik % 3600) / 60);

        return "{$hari}d {$jam}h {$menit}m";
    }
}