<?php

namespace App\Http\Controllers;

use App\Models\AplikasiDetail;
use Illuminate\Http\Request;
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
                    'server_name' => $a->nama_server ?? '-',
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
        $app = AplikasiDetail::where('id_aplikasi', $id)->first();

        abort_unless($app, 404);

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

        $maintenanceHistory = DB::table('vw_maintenance')
            ->where('target_type', 'Application')
            ->where('nama_target', $app->nama_aplikasi)
            ->orderBy('jadwal_tanggal', 'desc')
            ->limit(10)
            ->get();

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

            'server' => $app->nama_server ?? '-',
            'ip_address' => $app->alamat_ip_produksi ?? '-',
            'os' => $app->sistem_operasi ?? '-',
            'database' => $app->database_digunakan ?? '-',

            'domain' => $app->nama_domain ?? '-',
            'ssl_certificate' => $app->status_ssl ?? 'Tidak Ada',
            'ssl_expired' => $app->ssl_kadaluarsa 
                ? \Carbon\Carbon::parse($app->ssl_kadaluarsa)->format('d F Y')
                : '-',
            'domain_status' => $app->status_domain ?? 'Tidak Aktif',

            'license_name' => $app->licenses ?? '-',
            'license_provider' => '-',
            'license_count' => '-',
            'license_expired' => '-',
            'license_expired_note' => '',
            'license_status' => '-',

            'pic_name' => $app->nama_pic ?? '-',
            'pic_jabatan' => $app->pic_jabatan ?? '-',
            'pic_email' => $app->pic_email ?? '-',
            'pic_telepon' => $app->pic_telepon ?? '-',
            'pic_divisi' => '-',

            'last_maintenance' => $lastMaintenance ? [
                'tanggal' => \Carbon\Carbon::parse($lastMaintenance->jadwal_tanggal)->format('d F Y'),
                'jenis' => $lastMaintenance->jenis_maintenance,
                'pic' => $lastMaintenance->nama_pic ?? '-',
                'status' => $lastMaintenance->status,
            ] : null,

            'next_maintenance' => $nextMaintenance ? [
                'tanggal' => \Carbon\Carbon::parse($nextMaintenance->jadwal_tanggal)->format('d F Y'),
                'jenis' => $nextMaintenance->jenis_maintenance,
                'pic' => $nextMaintenance->nama_pic ?? '-',
                'status' => $nextMaintenance->status,
            ] : null,

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

    /**
     * Halaman form tambah aplikasi
     */
    public function create()
    {
        // Ambil data server buat dropdown
        $servers = DB::table('server')
            ->whereNull('deleted_at')
            ->orderBy('nama_server')
            ->pluck('nama_server', 'id_server');

        // Ambil data PIC buat dropdown
        $pics = DB::table('pic')
            ->orderBy('nama_pic')
            ->pluck('nama_pic', 'id_pic');

        return view('pages.application.create', compact('servers', 'pics'));
    }

    /**
     * Proses simpan aplikasi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_aplikasi' => 'required|string|max:20|unique:aplikasi,kode_aplikasi',
            'nama_aplikasi' => 'required|string|max:150',
            'id_server' => 'nullable|integer|exists:server,id_server',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:50',
            'versi' => 'nullable|string|max:20',
            'status' => 'required|in:Aktif,Down,Maintenance',
            'sla_percent' => 'nullable|numeric|min:0|max:100',
            'uptime_percent' => 'nullable|numeric|min:0|max:100',
            'database_digunakan' => 'nullable|string|max:50',
            'tanggal_mulai_operasi' => 'nullable|date',
            'id_pic' => 'nullable|integer|exists:pic,id_pic',
        ]);

        try {
            DB::table('aplikasi')->insert([
                'kode_aplikasi' => $request->kode_aplikasi,
                'nama_aplikasi' => $request->nama_aplikasi,
                'id_server' => $request->id_server,
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
                'versi' => $request->versi,
                'status' => $request->status,
                'sla_percent' => $request->sla_percent,
                'uptime_percent' => $request->uptime_percent,
                'database_digunakan' => $request->database_digunakan,
                'tanggal_mulai_operasi' => $request->tanggal_mulai_operasi,
                'id_pic' => $request->id_pic,
                'dibuat_pada' => now(),
            ]);

            return redirect()->route('application.index')
                ->with('success', 'Aplikasi berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan aplikasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Halaman form edit aplikasi
     */
    public function edit(int $id)
    {
        $app = DB::table('aplikasi')
            ->where('id_aplikasi', $id)
            ->whereNull('deleted_at')
            ->first();

        abort_unless($app, 404);

        // Ambil data server buat dropdown
        $servers = DB::table('server')
            ->whereNull('deleted_at')
            ->orderBy('nama_server')
            ->pluck('nama_server', 'id_server');

        // Ambil data PIC buat dropdown
        $pics = DB::table('pic')
            ->orderBy('nama_pic')
            ->pluck('nama_pic', 'id_pic');

        return view('pages.application.edit', compact('app', 'servers', 'pics'));
    }

    /**
     * Proses update aplikasi
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'kode_aplikasi' => 'required|string|max:20|unique:aplikasi,kode_aplikasi,' . $id . ',id_aplikasi',
            'nama_aplikasi' => 'required|string|max:150',
            'id_server' => 'nullable|integer|exists:server,id_server',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:50',
            'versi' => 'nullable|string|max:20',
            'status' => 'required|in:Aktif,Down,Maintenance',
            'sla_percent' => 'nullable|numeric|min:0|max:100',
            'uptime_percent' => 'nullable|numeric|min:0|max:100',
            'database_digunakan' => 'nullable|string|max:50',
            'tanggal_mulai_operasi' => 'nullable|date',
            'id_pic' => 'nullable|integer|exists:pic,id_pic',
        ]);

        try {
            DB::table('aplikasi')
                ->where('id_aplikasi', $id)
                ->update([
                    'kode_aplikasi' => $request->kode_aplikasi,
                    'nama_aplikasi' => $request->nama_aplikasi,
                    'id_server' => $request->id_server,
                    'deskripsi' => $request->deskripsi,
                    'kategori' => $request->kategori,
                    'versi' => $request->versi,
                    'status' => $request->status,
                    'sla_percent' => $request->sla_percent,
                    'uptime_percent' => $request->uptime_percent,
                    'database_digunakan' => $request->database_digunakan,
                    'tanggal_mulai_operasi' => $request->tanggal_mulai_operasi,
                    'id_pic' => $request->id_pic,
                    'diperbarui_pada' => now(),
                ]);

            return redirect()->route('application.index')
                ->with('success', 'Aplikasi berhasil diupdate!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate aplikasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Soft delete aplikasi
     */
    public function destroy(int $id)
    {
        try {
            DB::table('aplikasi')
                ->where('id_aplikasi', $id)
                ->update(['deleted_at' => now()]);

            return redirect()->route('application.index')
                ->with('success', 'Aplikasi berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus aplikasi: ' . $e->getMessage());
        }
    }
}