<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AlertController extends Controller
{
    public function index()
    {
        // ===== STATISTIK DARI VIEW =====
        $statsRaw = DB::table('vw_dashboard_stats')->first();

        $stats = [
            'total'    => $statsRaw->alert_aktif ?? 0,
            'critical' => $statsRaw->alert_critical ?? 0,
            'warning'  => DB::table('alert')->where('level', 'Warning')->where('status', 'Aktif')->count(),
            'info'     => DB::table('alert')->where('level', 'Info')->where('status', 'Aktif')->count(),
        ];

        // ===== AMBIL DATA ALERT DARI VIEW =====
        $allAlerts = DB::table('vw_alert')
            ->orderBy('waktu_mulai', 'desc')
            ->get()
            ->map(function ($row) {
                return [
                    'waktu'      => \Carbon\Carbon::parse($row->waktu_mulai)->format('d M Y'),
                    'jam'        => \Carbon\Carbon::parse($row->waktu_mulai)->format('H:i') . 
                                    ($row->waktu_selesai ? ' - ' . \Carbon\Carbon::parse($row->waktu_selesai)->format('H:i') : ''),
                    'level'      => $row->level,
                    'target'     => $row->nama_target ?? '-',
                    'target_sub' => $row->target_type,
                    'type'       => $row->target_type,
                    'deskripsi'  => $row->deskripsi,
                    'keterangan' => $row->keterangan ?? '',
                    'status'     => $row->status,
                    'durasi'     => $row->durasi_menit ? $row->durasi_menit . ' Menit' : '-',
                    'pic'        => $row->nama_pic ?? '-',
                ];
            });

        // ===== PISAHKAN PER KATEGORI =====
        $serverAlerts      = $allAlerts->where('type', 'Server')->values()->toArray();
        $applicationAlerts = $allAlerts->where('type', 'Application')->values()->toArray();
        $domainAlerts      = $allAlerts->where('type', 'Domain')->values()->toArray();

        return view('pages.alerts.alerts', compact(
            'stats',
            'serverAlerts',
            'applicationAlerts',
            'domainAlerts'
        ));
    }
}