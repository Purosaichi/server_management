<?php

namespace App\Http\Controllers;

use App\Models\ServerStatus;
use Illuminate\Support\Facades\DB;

class ServerController extends Controller
{
    /**
     * Halaman daftar server
     */
    public function index()
    {
        $servers = ServerStatus::orderBy('id_server')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id_server,
                    'name' => $s->nama_server,
                    'ip_address' => $s->alamat_ip_produksi ?? '-',
                    'status' => $s->status ?? 'Offline',
                    'cpu' => $s->cpu_percent ? $s->cpu_percent . '%' : '-',
                    'ram' => $s->memory_percent ? $s->memory_percent . '%' : '-',
                    'disk' => $s->storage_percent ? $s->storage_percent . '%' : '-',
                    'uptime' => $this->formatUptime($s->uptime_detik),
                ];
            })
            ->toArray();

        return view('pages.server.server', compact('servers'));
    }

    /**
     * Halaman detail server
     */
    public function show(int $id)
    {
        // Ambil data server dari view
        $serverView = ServerStatus::where('id_server', $id)->first();

        abort_unless($serverView, 404);

        // Ambil data teknis dari tabel server (join aset & pic)
        $serverDetail = DB::table('server')
            ->leftJoin('aset', 'aset.id_aset', '=', 'server.id_aset')
            ->leftJoin('pic', 'pic.id_pic', '=', 'server.id_pic')
            ->where('server.id_server', $id)
            ->select(
                'server.*',
                'aset.kode_aset',
                'aset.nama_aset',
                'aset.model_perangkat',
                'aset.nomor_seri',
                'aset.tanggal_pengadaan',
                'aset.tahun_pengadaan',
                'pic.nama_pic',
                'pic.jabatan',
                'pic.divisi',
                'pic.email',
                'pic.telepon'
            )
            ->first();

        // Ambil riwayat maintenance server ini
        $maintenanceHistory = DB::table('vw_maintenance')
            ->where('target_type', 'Server')
            ->where('nama_target', $serverView->nama_server)
            ->orderBy('jadwal_tanggal', 'desc')
            ->get();

        // Gabungin data
        $server = [
            'id' => $serverView->id_server,
            'name' => $serverView->nama_server,
            'subtitle' => 'Server Utama Aplikasi & Database',
            'status' => $serverView->status ?? 'Offline',
            'ip_address' => $serverView->alamat_ip_produksi ?? '-',
            'hostname' => $serverView->hostname ?? '-',
            'location' => $serverView->lokasi_rack ?? '-',
            'uptime' => $this->formatUptime($serverView->uptime_detik),
            'uptime_since' => $serverView->pengecekan_terakhir 
                ? \Carbon\Carbon::parse($serverView->pengecekan_terakhir)->format('d F Y, H:i')
                : '-',
            'os' => $serverView->sistem_operasi ?? '-',
            'status_monitoring' => ($serverView->status === 'Online') ? 'Aktif' : 'Tidak Aktif',
            'last_check' => $serverView->pengecekan_terakhir 
                ? \Carbon\Carbon::parse($serverView->pengecekan_terakhir)->format('d F Y, H:i:s')
                : '-',
            'last_check_note' => $serverView->pengecekan_terakhir 
                ? '(' . \Carbon\Carbon::parse($serverView->pengecekan_terakhir)->diffForHumans() . ')'
                : '',

            // Resource Utilization
            'cpu_percent' => $serverView->cpu_percent ?? 0,
            'cpu_detail' => $serverDetail->model_prosessor ?? '-',
            'memory_percent' => $serverView->memory_percent ?? 0,
            'memory_detail' => $serverDetail->kapasitas_memori_gb 
                ? $serverDetail->kapasitas_memori_gb . ' GB' 
                : '-',
            'storage_percent' => $serverView->storage_percent ?? 0,
            'storage_detail' => $serverDetail->kapasitas_penyimpanan_gb 
                ? $serverDetail->kapasitas_penyimpanan_gb . ' GB' 
                : '-',

            // Informasi Dasar
            'server_type' => $serverDetail->jenis_server ?? '-',
            'server_role' => 'Application & Database Server',
            'manufacture' => $serverDetail->model_perangkat ?? '-',
            'model' => $serverDetail->model_perangkat ?? '-',
            'serial_number' => $serverDetail->nomor_seri ?? '-',
            'purchase_date' => $serverDetail->tanggal_pengadaan 
                ? \Carbon\Carbon::parse($serverDetail->tanggal_pengadaan)->format('d F Y')
                : '-',
            'warranty' => '-',
            'server_status' => $serverDetail->status_aset ?? '-',

            // Spesifikasi Hardware
            'cpu_spec' => $serverDetail->model_prosessor ?? '-',
            'ram_spec' => $serverDetail->kapasitas_memori_gb 
                ? $serverDetail->kapasitas_memori_gb . ' GB ' . ($serverDetail->jenis_memori ?? '')
                : '-',
            'storage_spec' => $serverDetail->kapasitas_penyimpanan_gb 
                ? $serverDetail->kapasitas_penyimpanan_gb . ' GB ' . ($serverDetail->jenis_penyimpanan ?? '')
                : '-',

            // Informasi Jaringan
            'subnet_mask' => $serverDetail->subnet_mask ?? '-',
            'gateway' => $serverDetail->gateway ?? '-',
            'dns_server' => $serverDetail->dns_server ?? '-',
            'mac_address' => $serverDetail->mac_address ?? '-',
            'speed' => '1 Gbps',
            'network_usage_down' => '-',
            'network_usage_up' => '-',

            // Catatan Terakhir
            'last_note' => 'Tidak ada catatan',
            'last_note_by' => $serverDetail->nama_pic ?? '-',
            'last_note_date' => $serverView->pengecekan_terakhir 
                ? \Carbon\Carbon::parse($serverView->pengecekan_terakhir)->format('d F Y, H:i')
                : '-',

            // Riwayat Maintenance
            'maintenance_history' => $maintenanceHistory->map(function ($m) {
                return [
                    'tanggal' => \Carbon\Carbon::parse($m->jadwal_tanggal)->format('d F Y'),
                    'jenis' => $m->jenis_maintenance,
                    'pic' => $m->nama_pic ?? '-',
                    'status' => $m->status,
                ];
            })->toArray(),
        ];

        return view('pages.server.detail', compact('server'));
    }

    /**
     * Format uptime dari detik ke "Xd Yh Zm"
     */
    private function formatUptime($detik): string
    {
        if (!$detik) return '-';

        $hari = floor($detik / 86400);
        $jam = floor(($detik % 86400) / 3600);
        $menit = floor(($detik % 3600) / 60);

        return "{$hari}d {$jam}h {$menit}m";
    }
}