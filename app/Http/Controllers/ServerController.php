<?php

namespace App\Http\Controllers;

use App\Models\ServerStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ServerController extends Controller
{
    /*all servers data*/
    public function index()
    {
        $servers = $this->servers();

        return view('pages.server.server', compact('servers'));
    }

    /*detail server*/
   public function show(int $id)
{
    $server = collect($this->servers())->firstWhere('id', $id);

    abort_unless($server, 404);

    $assetDetails = DB::table('server')
        ->leftJoin('aset', 'aset.id_aset', '=', 'server.id_aset')
        ->leftJoin('merek', 'merek.id_merek', '=', 'aset.id_merek')
        ->where('server.id_server', $id)
        ->whereNull('server.deleted_at')
        ->select(
            'server.jenis_server',
            'server.server_role',
            'aset.id_merek',
            'aset.id_aset',
            'aset.id_lokasi_aset',
            'aset.model',
            'aset.nomor_seri',
            'aset.tanggal_pengadaan',
            'merek.nama_merek'
        )
        ->first();

    abort_unless($assetDetails, 404);

    $server['server_type'] = $assetDetails->jenis_server ?: '-';
    $server['server_role'] = $assetDetails->server_role ?: '-';
    $server['brand'] = $assetDetails->nama_merek ?: '-';
    $server['brand_id'] = $assetDetails->id_merek;
    $server['model'] = $assetDetails->model ?: '-';
    $server['model_input'] = $assetDetails->model ?? '';
    $server['serial_number'] = $assetDetails->nomor_seri ?: '-';
    $server['serial_number_input'] = $assetDetails->nomor_seri ?? '';
    $server['purchase_date_input'] = $assetDetails->tanggal_pengadaan
        ? Carbon::parse($assetDetails->tanggal_pengadaan)->format('Y-m-d')
        : '';
    $server['purchase_date'] = $assetDetails->tanggal_pengadaan
        ? Carbon::parse($assetDetails->tanggal_pengadaan)->locale('id')->translatedFormat('d F Y')
        : '-';
    $server = array_merge($server, $this->serverDetails($server));
    $assetLocation = DB::table('lokasi_aset')
        ->leftJoin('data_center', 'data_center.id_data_center', '=', 'lokasi_aset.id_data_center')
        ->where('lokasi_aset.id_lokasi_aset', $assetDetails->id_lokasi_aset)
        ->whereNull('lokasi_aset.deleted_at')
        ->orderBy('lokasi_aset.id_lokasi_aset')
        ->first([
            'data_center.nama_data_center',
            'lokasi_aset.nama_gedung',
            'lokasi_aset.lantai',
            'lokasi_aset.nama_ruangan',
            'lokasi_aset.zona',
            'lokasi_aset.kode_rak',
            'lokasi_aset.nama_rak',
        ]);
    $server['location'] = $assetLocation
        ? ($this->formatLocationLabel($assetLocation) ?: '-')
        : '-';
    $pics = DB::table('pic')->orderBy('nama_pic')->pluck('nama_pic', 'id_pic');
    $brands = DB::table('merek')
        ->whereNull('deleted_at')
        ->orderBy('nama_merek')
        ->pluck('nama_merek', 'id_merek');
    
    $applications = DB::table('aplikasi')
        ->where('id_server', $id)
        ->whereNull('deleted_at')
        ->orderBy('nama_aplikasi')
        ->get([
            'id_aplikasi',
            'kode_aplikasi',
            'nama_aplikasi',
            'kategori',
            'versi',
            'status',
        ]);

    $latestNote = DB::table('server_notes')
        ->leftJoin('pic', 'pic.id_pic', '=', 'server_notes.id_pic')
        ->where('server_notes.id_server', $id)
        ->whereNull('server_notes.deleted_at')
        ->orderBy('server_notes.dibuat_pada', 'desc')
        ->select(
            'server_notes.id_note',
            'server_notes.catatan',
            'server_notes.id_pic',
            'server_notes.dibuat_pada',
            'server_notes.diperbarui_pada',
            'pic.nama_pic',
            'pic.jabatan'
        )
        ->first();

    return view('pages.server.detail', compact('server', 'pics', 'applications', 'latestNote', 'brands'));
}

    /*form tambah server*/
    public function create()
    {
        $locations = $this->assetLocations();

        return view('pages.server.create', compact('locations'));
    }

    /*simpan server baru*/
    public function store(Request $request)
    {
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
            'lokasi_aset_id' => ['nullable', 'integer', Rule::exists('lokasi_aset', 'id_lokasi_aset')->whereNull('deleted_at')],
            'status_aset' => 'required|in:Aktif,Tidak Aktif,Dihapus',
            'status_monitoring' => 'required|in:Online,Offline',
            'cpu_percent' => 'nullable|integer|min:0|max:100',
            'memory_percent' => 'nullable|integer|min:0|max:100',
            'storage_percent' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            $idAset = DB::table('aset')->insertGetId([
                'kode_aset' => 'AST-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'nama_aset' => $request->nama_server,
                'status_aset' => $request->status_aset,
                'kondisi_aset' => 'Baik',
                'status_kepemilikan' => 'Milik Sendiri',
                'tanggal_pengadaan' => now()->format('Y-m-d'),
                'tahun_pengadaan' => date('Y'),
                'keterangan' => 'Server baru ditambahkan via web',
                'id_lokasi_aset' => $request->integer('lokasi_aset_id') ?: null,
            ]);

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
                'lokasi_rack' => null,
            ]);

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

    /*form edit server*/
    public function edit(int $id)
    {
        /* Ambil data teknis dari tabel server + aset */
        $serverDetail = DB::table('server')
            ->leftJoin('aset', 'aset.id_aset', '=', 'server.id_aset')
            ->where('server.id_server', $id)
            ->select(
                'server.*',
                'aset.nama_aset',
                'aset.status_aset',
                'aset.id_lokasi_aset',
            )
            ->firstOrFail();

        /* Ambil data monitoring terbaru */
        $monitoring = DB::table('server_monitoring')
            ->where('id_server', $id)
            ->orderBy('dicatat_pada', 'desc')
            ->first();
        $assetLocation = DB::table('lokasi_aset')
            ->where('id_lokasi_aset', $serverDetail->id_lokasi_aset)
            ->whereNull('deleted_at')
            ->first();
        $locations = $this->assetLocations();

        /* Gabungin data buat view */
        $server = (object) [
            'id_server' => $serverDetail->id_server,
            'nama_server' => $serverDetail->nama_server,
            'hostname' => $serverDetail->hostname,
            'alamat_ip_manajemen' => $serverDetail->alamat_ip_manajemen,
            'alamat_ip_produksi' => $serverDetail->alamat_ip_produksi,
            'sistem_operasi' => $serverDetail->sistem_operasi,
            'jenis_server' => $serverDetail->jenis_server,
            'jumlah_prosessor' => $serverDetail->jumlah_prosessor,
            'model_prosessor' => $serverDetail->model_prosessor,
            'kapasitas_memori_gb' => $serverDetail->kapasitas_memori_gb,
            'jenis_memori' => $serverDetail->jenis_memori,
            'kapasitas_penyimpanan_gb' => $serverDetail->kapasitas_penyimpanan_gb,
            'jenis_penyimpanan' => $serverDetail->jenis_penyimpanan,
            'id_lokasi_aset' => $serverDetail->id_lokasi_aset,
            'lokasi_rack' => $serverDetail->lokasi_rack,
            'status_aset' => $serverDetail->status_aset,
            'status_monitoring' => $monitoring->status ?? 'Offline',
            'cpu_percent' => $monitoring->cpu_percent ?? 0,
            'memory_percent' => $monitoring->memory_percent ?? 0,
            'storage_percent' => $monitoring->storage_percent ?? 0,
        ];

        return view('pages.server.edit', compact('server', 'locations'));
    }

    /*proses update server*/
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_server' => 'required|string|max:50|unique:server,nama_server,' . $id . ',id_server',
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
            'lokasi_aset_id' => ['nullable', 'integer', Rule::exists('lokasi_aset', 'id_lokasi_aset')->whereNull('deleted_at')],
            'status_aset' => 'required|in:Aktif,Tidak Aktif,Dihapus',
            'status_monitoring' => 'required|in:Online,Offline',
            'cpu_percent' => 'nullable|integer|min:0|max:100',
            'memory_percent' => 'nullable|integer|min:0|max:100',
            'storage_percent' => 'nullable|integer|min:0|max:100',
        ]);

        $server = DB::table('server')->where('id_server', $id)->first();
        abort_unless($server, 404);

        try {
            DB::beginTransaction();

            /* Update tabel aset */
            DB::table('aset')
                ->where('id_aset', $server->id_aset)
                ->update([
                    'nama_aset' => $request->nama_server,
                    'status_aset' => $request->status_aset,
                    'id_lokasi_aset' => $request->integer('lokasi_aset_id') ?: null,
                ]);

            /* Update tabel server */
            DB::table('server')
                ->where('id_server', $id)
                ->update([
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
                ]);

            /* Insert monitoring baru (biar status up-to-date) */
            DB::table('server_monitoring')->insert([
                'id_server' => $id,
                'status' => $request->status_monitoring,
                'cpu_percent' => $request->cpu_percent ?? 0,
                'memory_percent' => $request->memory_percent ?? 0,
                'storage_percent' => $request->storage_percent ?? 0,
                'uptime_detik' => $request->status_monitoring === 'Online' ? 3600 : 0,
                'dicatat_pada' => now(),
            ]);

            DB::commit();

            return redirect()->route('server.index')
                ->with('success', 'Server berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate server: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateBasicInfo(Request $request, int $id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'jenis_server' => 'required|in:Physical,Virtual',
            'server_role' => 'nullable|string|max:255',
            'id_merek' => 'nullable|exists:merek,id_merek',
            'model' => 'nullable|string|max:255',
            'nomor_seri' => 'nullable|string|max:100',
            'tanggal_pengadaan' => 'nullable|date',
            'status_monitoring' => 'required|in:Online,Offline',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'basicInfo')
                ->withInput();
        }

        $server = DB::table('server')->where('id_server', $id)->first();
        abort_unless($server, 404);

        try {
            DB::beginTransaction();

            DB::table('server')
                ->where('id_server', $id)
                ->update([
                    'jenis_server' => $request->jenis_server,
                    'server_role' => $request->server_role,
                ]);

            DB::table('aset')
                ->where('id_aset', $server->id_aset)
                ->update([
                    'id_merek' => $request->id_merek ?: null,
                    'model' => $request->model,
                    'nomor_seri' => $request->nomor_seri,
                    'tanggal_pengadaan' => $request->tanggal_pengadaan,
                ]);

            $latestMonitoring = DB::table('server_monitoring')
                ->where('id_server', $id)
                ->orderByDesc('dicatat_pada')
                ->first();

            DB::table('server_monitoring')->insert([
                'id_server' => $id,
                'status' => $request->status_monitoring,
                'cpu_percent' => $latestMonitoring->cpu_percent ?? 0,
                'memory_percent' => $latestMonitoring->memory_percent ?? 0,
                'storage_percent' => $latestMonitoring->storage_percent ?? 0,
                'uptime_detik' => $request->status_monitoring === 'Online'
                    ? ($latestMonitoring->uptime_detik ?? 3600)
                    : 0,
                'dicatat_pada' => now(),
            ]);

            DB::commit();

            return redirect()->route('server.detail', $id)
                ->with('success', 'Informasi dasar server berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal memperbarui informasi dasar server: ' . $e->getMessage())
                ->withInput();
        }
    }

    /*soft delete */
    public function destroy(int $id)
    {
        try {
            DB::table('server')
                ->where('id_server', $id)
                ->update(['deleted_at' => now()]);

            return redirect()->route('server.index')
                ->with('success', 'Server berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus server: ' . $e->getMessage());
        }
    }

    /*data detail server*/
    private function serverDetails(array $server): array
{
    $isOffline = $server['status'] === 'Offline';

    /* Ambil data jaringan dari database */
    $networkData = DB::table('vw_server_status')
        ->where('id_server', $server['id'])
        ->first();
    $lastCheckAt = $networkData->pengecekan_terakhir ?? null;
    $lastCheck = $lastCheckAt ? Carbon::parse($lastCheckAt)->locale('id') : null;

    $maintenanceHistory = DB::table('vw_maintenance')
        ->where('target_type', 'Server')
        ->where('nama_target', $server['name'])
        ->orderByDesc('jadwal_tanggal')
        ->limit(3)
        ->get()
        ->map(function ($maintenance) {
            return [
                'id' => $maintenance->id_maintenance,
                'tanggal' => $maintenance->jadwal_tanggal
                    ? Carbon::parse($maintenance->jadwal_tanggal)->translatedFormat('d F Y, H:i')
                    : '-',
                'tanggal_input' => $maintenance->jadwal_tanggal
                    ? Carbon::parse($maintenance->jadwal_tanggal)->format('Y-m-d')
                    : '',
                'jam_mulai' => $maintenance->jam_mulai ? substr($maintenance->jam_mulai, 0, 5) : '',
                'jam_selesai' => $maintenance->jam_selesai ? substr($maintenance->jam_selesai, 0, 5) : '',
                'jenis' => $maintenance->jenis_maintenance ?? '-',
                'pic' => $maintenance->nama_pic ?? '-',
                'id_pic' => $maintenance->id_pic ?? null,
                'status' => $maintenance->status,
                'catatan' => $maintenance->catatan ?? '',
                'editable' => in_array($maintenance->status, ['Scheduled', 'In Progress'], true),
            ];
        })
        ->toArray();

    return [
        'ip_address' => $server['ip_address'] ?? '-',
        'subtitle' => 'Server Utama Aplikasi & Database',
        'hostname' => strtolower($server['name']) . '.kemendik.local',
        'location' => '-',
        'uptime_since' => $isOffline ? '-' : '15 Juli 2026, 12:00',
        'os' => $isOffline ? '-' : 'Windows Server 2025 Standard',
        'status_monitoring' => $isOffline ? 'Tidak Aktif' : 'Aktif',
        'last_check' => $lastCheck?->translatedFormat('d F Y, H:i:s') ?? '-',
        'last_check_note' => $lastCheck ? '(' . $lastCheck->diffForHumans() . ')' : '-',

        'cpu_percent' => $isOffline ? 0 : (int) str_replace('%', '', $server['cpu']),
        'cpu_detail' => $isOffline ? '-' : '2 Core (4.2 GHz)',
        'memory_percent' => $isOffline ? 0 : (int) str_replace('%', '', $server['ram']),
        'memory_detail' => $isOffline ? '-' : '98 GB / 128 GB',
        'storage_percent' => $isOffline ? 0 : (int) str_replace('%', '', $server['disk']),
        'storage_detail' => $isOffline ? '-' : '85 TB / 100 TB',

        'server_type' => $server['server_type'] ?? '-',
        'server_role' => $server['server_role'] ?? '-',
        'manufacture' => $server['brand'],
        'model' => $server['model'] ?? '-',
        'serial_number' => $server['serial_number'] ?? '-',
        'purchase_date' => $server['purchase_date'] ?? '-',
        'warranty' => $server['warranty'] ?? '-',
        'server_status' => $server['status'],

        'cpu_spec' => $isOffline ? '-' : 'AMD EPYC 9655 (96 Core/192 Thread)',
        'ram_spec' => $isOffline ? '-' : '1024 GB DDR5 ECC RDIMM',
        'storage_spec' => $isOffline ? '-' : '10 TB NVMe Enterprise U.2/U.3',

        /* DATA JARINGAN (DARI DATABASE) */
        'subnet_mask' => $networkData->subnet_mask ?? '-',
        'gateway' => $networkData->gateway ?? '-',
        'dns_server' => $networkData->dns_server ?? '-',
        'mac_address' => $networkData->mac_address ?? '-',
        'speed' => $networkData->network_speed ?? '-',
        'network_usage_down' => $networkData->network_usage_down ?? '-',
        'network_usage_up' => $networkData->network_usage_up ?? '-',

        'last_note' => $isOffline ? 'Server sedang offline' : 'Disk sudah mulai penuh',
        'last_note_by' => 'Fathier Assyarief',
        'last_note_date' => $isOffline ? '-' : '12 Agustus 2026, 12:12',

        'maintenance_history' => $maintenanceHistory,
    ];
}

    /*ambil data server dari view vw_server_status*/
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

    /*Format uptime dari detik ke "Xd Yh Zm"*/
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

    private function assetLocations()
    {
        return DB::table('lokasi_aset')
            ->leftJoin('data_center', 'data_center.id_data_center', '=', 'lokasi_aset.id_data_center')
            ->whereNull('lokasi_aset.deleted_at')
            ->orderBy('data_center.nama_data_center')
            ->orderBy('lokasi_aset.nama_gedung')
            ->orderBy('lokasi_aset.nama_rak')
            ->get([
                'lokasi_aset.id_lokasi_aset',
                'lokasi_aset.id_data_center',
                'data_center.nama_data_center',
                'lokasi_aset.nama_gedung',
                'lokasi_aset.lantai',
                'lokasi_aset.nama_ruangan',
                'lokasi_aset.zona',
                'lokasi_aset.kode_rak',
                'lokasi_aset.nama_rak',
                'lokasi_aset.tinggi_rak',
                'lokasi_aset.posisi_rak',
                'lokasi_aset.tanggal_penempatan',
                'lokasi_aset.keterangan',
            ])
            ->map(function ($location) {
                $location->label = $this->formatLocationLabel($location)
                    ?: 'Lokasi aset #' . $location->id_lokasi_aset;

                return $location;
            });
    }

    private function formatLocationLabel(object $location): string
    {
        return collect([
            $location->nama_data_center,
            $location->nama_gedung,
            $location->lantai,
            $location->nama_ruangan,
            $location->zona,
            $location->kode_rak,
            $location->nama_rak,
        ])->filter()->implode(' - ');
    }

    /**
 * Update informasi jaringan server
 */
    public function updateNetwork(Request $request, int $id)
    {
        $request->validate([
            'alamat_ip_manajemen' => 'nullable|string|max:45',
            'alamat_ip_produksi' => 'nullable|string|max:45',
            'subnet_mask' => 'nullable|string|max:45',
            'gateway' => 'nullable|string|max:45',
            'dns_server' => 'nullable|string|max:100',
            'mac_address' => 'nullable|string|max:20',
            'network_speed' => 'nullable|string|max:20',
            'network_usage_down' => 'nullable|string|max:20',
            'network_usage_up' => 'nullable|string|max:20',
        ]);

        try {
            DB::table('server')
                ->where('id_server', $id)
                ->update([
                    'alamat_ip_manajemen' => $request->alamat_ip_manajemen,
                    'alamat_ip_produksi' => $request->alamat_ip_produksi,
                    'subnet_mask' => $request->subnet_mask,
                    'gateway' => $request->gateway,
                    'dns_server' => $request->dns_server,
                    'mac_address' => $request->mac_address,
                    'network_speed' => $request->network_speed,
                    'network_usage_down' => $request->network_usage_down,
                    'network_usage_up' => $request->network_usage_up,
                ]);

            return redirect()->back()->with('success', 'Informasi jaringan berhasil diupdate!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate informasi jaringan: ' . $e->getMessage())
                ->withInput();
        }
    }
}