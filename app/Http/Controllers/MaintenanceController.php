<?php

// ============================================================
// FEATURE: MAINTENANCE
// ============================================================

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    public function index()
    {
        // ===== STATISTIK =====
        $total      = DB::table('maintenance')->count();
        $completed  = DB::table('maintenance')->where('status', 'Completed')->count();
        $inProgress = DB::table('maintenance')->where('status', 'In Progress')->count();
        $scheduled  = DB::table('maintenance')->where('status', 'Scheduled')->count();

        $stats = [
            'total'       => $total,
            'completed'   => $completed,
            'in_progress' => $inProgress,
            'scheduled'   => $scheduled,
            'period'      => Carbon::now()->translatedFormat('F Y'),
        ];

        // ===== AMBIL DATA DARI VIEW =====
        $allMaintenances = DB::table('vw_maintenance')
            ->orderBy('jadwal_tanggal', 'asc')
            ->get()
            ->map(function ($row) {
                return [
                    'id'         => $row->kode_maintenance,
                    'target'     => $row->nama_target ?? '-',
                    'target_sub' => $row->target_type ?? '-',
                    'jenis'      => $row->jenis_maintenance ?? '-',
                    'kategori'   => $row->target_type ?? '-',
                    'jadwal'     => $row->jadwal_tanggal
                        ? Carbon::parse($row->jadwal_tanggal)->format('d M Y')
                        : '-',
                    'jam'        => ($row->jam_mulai ? substr($row->jam_mulai, 0, 5) : '-') .
                                    ($row->jam_selesai ? ' - ' . substr($row->jam_selesai, 0, 5) : ''),
                    'durasi'     => $row->durasi_menit ? $row->durasi_menit . ' Menit' : '-',
                    'pic'        => $row->nama_pic ?? '-',
                    'status'     => $row->status,
                ];
            });

        // ===== PISAHKAN PER KATEGORI =====
        $ServerMaintenance      = $allMaintenances->where('kategori', 'Server')->values()->toArray();
        $ApplicationMaintenance = $allMaintenances->where('kategori', 'Application')->values()->toArray();
        $DomainMaintenance      = $allMaintenances->where('kategori', 'Domain')->values()->toArray();

        // ===== UNTUK TABEL UTAMA =====
        $maintenances = $allMaintenances->toArray();

        return view('pages.maintenance.maintenance', compact(
            'stats',
            'maintenances',
            'ServerMaintenance',
            'ApplicationMaintenance',
            'DomainMaintenance'
        ))->with([
            'serverMaintenances'      => $ServerMaintenance,
            'applicationMaintenances' => $ApplicationMaintenance,
            'domainMaintenances'      => $DomainMaintenance,
        ]);
    }
}