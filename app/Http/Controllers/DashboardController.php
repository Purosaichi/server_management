<?php

namespace App\Http\Controllers;

use App\Models\DashboardStat;
use App\Models\ServerStatus;
use App\Models\AplikasiDetail;
use App\Models\ReminderKadaluarsa;

class DashboardController extends Controller
{
    public function index()
    {
         $stats = DashboardStat::first();

        // ===== SERVER TERBARU (6 data) =====
        $servers = ServerStatus::orderBy('id_server')
            ->limit(6)
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

        // ===== APLIKASI TERBARU (5 data) =====
        $applications = AplikasiDetail::orderBy('id_aplikasi')
            ->limit(5)
            ->get()
            ->map(function ($a) {
                return [
                    'id' => $a->id_aplikasi,
                    'name' => $a->nama_aplikasi,
                    'server' => $a->nama_server ?? '-',
                    'status' => $a->status_aplikasi,
                    'domain' => $a->nama_domain ?? '-',
                    'licenses' => '-',  // nanti diisi dari relasi lisensi
                    'maintenance' => '-',
                ];
            })
            ->toArray();

        // ===== REMINDER KADALUARSA (yang < 60 hari) =====
        $reminders = ReminderKadaluarsa::where('sisa_hari', '<=', 60)
            ->orderBy('sisa_hari')
            ->limit(5)
            ->get();

        return view('pages.home.dashboard', compact(
            'stats',
            'servers',
            'applications',
            'reminders'
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