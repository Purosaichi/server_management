<?php

namespace App\Http\Controllers;

use App\Models\ServerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     */
    public function show(int $id)
    {
        $server = collect($this->servers())->firstWhere('id', $id);

        abort_unless($server, 404);

        $server = array_merge($server, $this->serverDetails($server));

        return view('pages.server.detail', compact('server'));
    }

    /**
     * Halaman form tambah server
     */
    public function create()
    {
        return view('pages.server.create');
    }

    /**
     * Proses simpan server baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_server' => 'required|string|max:50|unique:server,nama_server',
            'hostname' => 'nullable|string|max:100',
            'alamat_ip_manajemen' => 'nullable|string|max:45',
            'alamat_ip_produksi' => 'nullable|string|max:45',
            'sistem_operasi' => 'nullable|string|max:100',
            'jenis_server' => 'nullable|in:Physical,Virtual',
            'jumlah_prosessor' => 'nullable|integer',
            'model_prosessor' => 'nullable|string|max:100',
            'kapasitas_memori_gb' => 'nullable|integer',
            'jenis_memori' => 'nullable|string|max:30',
            'kapasitas_penyimpanan_gb' => 'nullable|integer',
            'jenis_penyimpanan' => 'nullable|string|max:30',
            'lokasi_rack' => 'nullable|string|max:100',
            'status_aset' => 'required|in:Aktif,Tidak Aktif,Dihapus',
            'status_monitoring' => 'required|in:Online,Offline',
            'cpu_percent' => 'nullable|integer|min:0|max:100',
            'memory_percent' => 'nullable|integer|min:0|max:100',
            'storage_percent' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            // 1. Insert ke tabel ASET
            $idAset = DB::table('aset')->insertGetId([
                'kode_aset' => 'AST-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'nama_aset' => $request->nama_server,
                'status_aset' => $request->status_aset,
                'kondisi_aset' => 'Baik',
                'status_kepemilikan' => 'Milik Sendiri',
                'tanggal_pengadaan' => now()->format('Y-m-d'),
                'tahun_pengadaan' => date('Y'),
                'keterangan' => 'Server baru ditambahkan via web',
            ]);

            // 2. Insert ke tabel SERVER
            $idServer = DB::table('server')->insertGetId([
                'id_aset' => $idAset,
                'nama_server' => $request->nama_server,
                'hostname' => $request->hostname,
                'alamat_ip_manajemen' => $request->alamat_ip_manajemen,
                'alamat_ip_produksi' => $request->alamat_ip_produksi,
                'sistem_operasi' => $request->sistem_operasi,
                'jenis_server' => $request->jenis_server,
                'jumlah_prosessor' => $request->jumlah_prosessor,
                'model_prosessor' => $request->model_prosessor,
                'kapasitas_memori_gb' => $request->kapasitas_memori_gb,
                'jenis_memori' => $request->jenis_memori,
                'kapasitas_penyimpanan_gb' => $request->kapasitas_penyimpanan_gb,
                'jenis_penyimpanan' => $request->jenis_penyimpanan,
                'lokasi_rack' => $request->lokasi_rack,
            ]);

            // 3. Insert ke SERVER_MONITORING (biar status muncul di view)
            DB::table('server_monitoring')->insert([
                'id_server' => $idServer,
                'status' => $request->status_monitoring,
                'cpu_percent' => $request->cpu_percent ?? 0,
                'memory_percent' => $request->memory_percent ?? 0,
                'storage_percent' => $request->storage_percent ?? 0,
                'uptime_detik' => $request->status_monitoring === 'Online' ? 3600 : 0,
                'dicatat_pada' => now(),
            ]);

            DB::commit();

            return redirect()->route('server.index')
                ->with('success', 'Server berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan server: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Data detail server (buat halaman detail)
     */
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

    /**
     * Ambil data server dari view vw_server_status
     */
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

    /**
     * Format uptime dari detik ke "Xd Yh Zm"
     */
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