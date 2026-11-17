<?php


namespace App\Http\Controllers;

use App\Models\DashboardStat;
use App\Models\ServerStatus;
use App\Models\AplikasiDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
         $stats = DashboardStat::first();

        /*card server */
        $servers = ServerStatus::orderBy('id_server')
            ->limit(3)
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

        /*card app */
        $maintenance = \DB::table('vw_maintenance')
            ->where('target_type', 'Application')
            ->orderBy('jadwal_tanggal')
            ->get()
            ->groupBy('nama_target');

        $applications = AplikasiDetail::orderBy('id_aplikasi')
            ->limit(3)
            ->get()
            ->map(function ($a) use ($maintenance) {
                $next = $maintenance->get($a->nama_aplikasi, collect())->first();

                return [
                    'id' => $a->id_aplikasi,
                    'name' => $a->nama_aplikasi,
                    'server' => $a->nama_server ?? '-',
                    'status' => $a->status_aplikasi,
                    'domain' => $a->nama_domain ?? '-',
                    'licenses' => $a->database_digunakan ?? '-',
                    'maintenance' => $next->jadwal_tanggal ?? '-',
                ];
            })
            ->toArray();

        $domainCount = DB::table('domain')
            ->whereNull('deleted_at')
            ->count();
        $activeDomainIds = DB::table('domain')
            ->whereNull('deleted_at')
            ->select('id_domain');
        $domains = DB::table('vw_domain_detail')
            ->whereIn('id_domain', $activeDomainIds)
            ->orderBy('nama_domain')
            ->limit(3)
            ->get([
                'id_domain',
                'nama_domain',
                'nama_aplikasi',
                'status_domain',
                'nama_pic',
                'ssl_status',
                'tanggal_kadaluarsa',
            ])
            ->map(function ($domain) {
                return [
                    'id' => $domain->id_domain,
                    'domain' => $domain->nama_domain,
                    'application' => $domain->nama_aplikasi ?? '_',
                    'status' => $domain->status_domain ?? '-',
                    'pic' => $domain->nama_pic ?? '_',
                    'ssl' => $domain->ssl_status ?? 'tidak ada',
                    'last_activity' => $domain->tanggal_kadaluarsa
                        ? Carbon::parse($domain->tanggal_kadaluarsa)->format('d F Y')
                        : '_',
                ];
            });

        return view('pages.home.dashboard', compact(
            'stats',
            'servers',
            'applications',
            'domains',
            'domainCount'
        ));
    }

    private function formatUptime($detik): string
    {
        if (!$detik) return '-';

        $hari = floor($detik / 86400);
        $jam = floor(($detik % 86400) / 3600);
        $menit = floor(($detik % 3600) / 60);

        return "{$hari}d {$jam}h {$menit}m";
    }
}