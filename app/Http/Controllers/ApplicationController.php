<?php

namespace App\Http\Controllers;

use App\Models\AplikasiDetail;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    /**
     * Halaman daftar aplikasi
     */
    public function index()
    {
        $applications = AplikasiDetail::orderBy('id_aplikasi')
            ->get()
            ->map(function ($a) {
                return [
                    'id' => $a->id_aplikasi,
                    'name' => $a->nama_aplikasi,
                    'server_name' => $a->nama_server ?? '-',  // ← ganti dari 'server'
                    'status' => $a->status_aplikasi,
                    'domain' => $a->nama_domain ?? '-',
                    'licenses' => $a->licenses ?? '-',
                    'next_maintenance' => $a->next_maintenance ?? '-',
                ];
            })
            ->toArray();

        return view('pages.application.application', compact('applications'));
    }

    /**
     * Halaman detail aplikasi
     */
    public function show(int $id)
    {
        // Ambil data dari view
        $app = AplikasiDetail::where('id_aplikasi', $id)->first();

        abort_unless($app, 404);

        // Ambil data teknis dari tabel aplikasi
        $appDetail = DB::table('aplikasi')
            ->where('id_aplikasi', $id)
            ->first();

        // Ambil maintenance terakhir & berikutnya
        $lastMaintenance = DB::table('vw_maintenance')
            ->where('target_type', 'Application')
            ->where('nama_target', $app->nama_aplikasi)
            ->where('status', 'Completed')
            ->orderBy('jadwal_tanggal', 'desc')
            ->first();

        $nextMaintenance = DB::table('vw_maintenance')
            ->where('target_type', 'Application')
            ->where('nama_target', $app->nama_aplikasi)
            ->whereIn('status', ['Scheduled', 'In Progress'])
            ->orderBy('jadwal_tanggal', 'asc')
            ->first();

        // Ambil riwayat maintenance
        $maintenanceHistory = DB::table('vw_maintenance')
            ->where('target_type', 'Application')
            ->where('nama_target', $app->nama_aplikasi)
            ->orderBy('jadwal_tanggal', 'desc')
            ->limit(10)
            ->get();

        // Format data buat view
        $application = [
            'id' => $app->id_aplikasi,
            'name' => $app->nama_aplikasi,
            'status' => $app->status_aplikasi,
            'description' => $app->deskripsi ?? 'Aplikasi untuk mengelola sumber daya guru untuk keperluan pendidikan',
            'code' => $app->kode_aplikasi,
            'category' => $app->kategori ?? '-',
            'sla' => $app->sla_percent ? $app->sla_percent . '%' : '-',
            'version' => $app->versi ?? '-',
            'uptime' => $app->uptime_percent ? $app->uptime_percent . '%' : '0%',
            'uptime_period' => '30 Hari Terakhir',
            'since' => $app->tanggal_mulai_operasi 
                ? \Carbon\Carbon::parse($app->tanggal_mulai_operasi)->format('d F Y')
                : '-',
            'since_duration' => $app->tanggal_mulai_operasi 
                ? '(' . \Carbon\Carbon::parse($app->tanggal_mulai_operasi)->diffForHumans(null, true) . ')'
                : '',

            // Server & Infrastruktur
            'server' => $app->nama_server ?? '-',
            'ip_address' => $app->alamat_ip_produksi ?? '-',
            'os' => $app->sistem_operasi ?? '-',
            'database' => $app->database_digunakan ?? '-',

            // Domain
            'domain' => $app->nama_domain ?? '-',
            'ssl_certificate' => $app->status_ssl ?? 'Tidak Ada',
            'ssl_expired' => $app->ssl_kadaluarsa 
                ? \Carbon\Carbon::parse($app->ssl_kadaluarsa)->format('d F Y')
                : '-',
            'domain_status' => $app->status_domain ?? 'Tidak Aktif',

            // Licenses
            'license_name' => $app->licenses ?? '-',
            'license_provider' => '-',
            'license_count' => '-',
            'license_expired' => '-',
            'license_expired_note' => '',
            'license_status' => '-',

            // Pengurus / PIC
            'pic_name' => $app->nama_pic ?? '-',
            'pic_jabatan' => $app->pic_jabatan ?? '-',
            'pic_email' => $app->pic_email ?? '-',
            'pic_telepon' => $app->pic_telepon ?? '-',
            'pic_divisi' => '-',

            // Maintenance Terakhir
            'last_maintenance' => $lastMaintenance ? [
                'tanggal' => \Carbon\Carbon::parse($lastMaintenance->jadwal_tanggal)->format('d F Y'),
                'jenis' => $lastMaintenance->jenis_maintenance,
                'pic' => $lastMaintenance->nama_pic ?? '-',
                'status' => $lastMaintenance->status,
            ] : null,

            // Maintenance Berikutnya
            'next_maintenance' => $nextMaintenance ? [
                'tanggal' => \Carbon\Carbon::parse($nextMaintenance->jadwal_tanggal)->format('d F Y'),
                'jenis' => $nextMaintenance->jenis_maintenance,
                'pic' => $nextMaintenance->nama_pic ?? '-',
                'status' => $nextMaintenance->status,
            ] : null,

            // Riwayat Maintenance
            'maintenance_history' => $maintenanceHistory->map(function ($m) {
                return [
                    'tanggal' => \Carbon\Carbon::parse($m->jadwal_tanggal)->format('d F Y'),
                    'jenis' => $m->jenis_maintenance,
                    'status' => $m->status,
                ];
            })->toArray(),
        ];

        return view('pages.application.detail', compact('application'));
    }
}