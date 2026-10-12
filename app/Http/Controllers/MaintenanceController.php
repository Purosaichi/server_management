<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    /**
     * Halaman daftar maintenance
     */
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
                    'id'         => $row->id_maintenance,
                    'kode'       => $row->kode_maintenance,
                    'target'     => $row->nama_target ?? '-',
                    'target_sub' => $row->target_type ?? '-',
                    'jenis'      => $row->jenis_maintenance ?? '-',
                    'kategori'   => $row->target_type ?? '-',
                    'jadwal'     => $row->jadwal_tanggal
                        ? Carbon::parse($row->jadwal_tanggal)->format('d M Y')
                        : '-',
                    'jadwal_raw' => $row->jadwal_tanggal,
                    'jam_mulai'  => $row->jam_mulai ? substr($row->jam_mulai, 0, 5) : '',
                    'jam_selesai'=> $row->jam_selesai ? substr($row->jam_selesai, 0, 5) : '',
                    'jam'        => ($row->jam_mulai ? substr($row->jam_mulai, 0, 5) : '-') .
                                    ($row->jam_selesai ? ' - ' . substr($row->jam_selesai, 0, 5) : ''),
                    'durasi'     => $row->durasi_menit ? $row->durasi_menit . ' Menit' : '-',
                    'pic'        => $row->nama_pic ?? '-',
                    'status'     => $row->status,
                    'catatan'    => $row->catatan ?? '',
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

    /**
     * Simpan maintenance baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_aplikasi'       => 'required|integer|exists:aplikasi,id_aplikasi',
            'jenis_maintenance' => 'required|string|max:100',
            'jadwal_tanggal'    => 'required|date',
            'jam_mulai'         => 'nullable',
            'jam_selesai'       => 'nullable',
            'status'            => 'required|in:Scheduled,In Progress,Completed,Canceled',
            'catatan'           => 'nullable|string',
        ]);

        try {
            // Generate kode maintenance otomatis
            $lastKode = DB::table('maintenance')
                ->where('kode_maintenance', 'like', 'MTN-APP-%')
                ->orderBy('id_maintenance', 'desc')
                ->value('kode_maintenance');

            $lastNumber = $lastKode ? (int) substr($lastKode, -3) : 0;
            $newKode = 'MTN-APP-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            DB::table('maintenance')->insert([
                'kode_maintenance'  => $newKode,
                'target_type'       => 'Application',
                'id_aplikasi'       => $request->id_aplikasi,
                'jenis_maintenance' => $request->jenis_maintenance,
                'jadwal_tanggal'    => $request->jadwal_tanggal,
                'jam_mulai'         => $request->jam_mulai ?: null,
                'jam_selesai'       => $request->jam_selesai ?: null,
                'status'            => $request->status,
                'catatan'           => $request->catatan,
                'dibuat_pada'       => now(),
            ]);

            return redirect()->back()->with('success', 'Jadwal maintenance berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan maintenance: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update maintenance
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'jenis_maintenance' => 'required|string|max:100',
            'jadwal_tanggal'    => 'required|date',
            'jam_mulai'         => 'nullable',
            'jam_selesai'       => 'nullable',
            'status'            => 'required|in:Scheduled,In Progress,Completed,Canceled',
            'catatan'           => 'nullable|string',
        ]);

        try {
            DB::table('maintenance')
                ->where('id_maintenance', $id)
                ->update([
                    'jenis_maintenance' => $request->jenis_maintenance,
                    'jadwal_tanggal'    => $request->jadwal_tanggal,
                    'jam_mulai'         => $request->jam_mulai ?: null,
                    'jam_selesai'       => $request->jam_selesai ?: null,
                    'status'            => $request->status,
                    'catatan'           => $request->catatan,
                ]);

            return redirect()->back()->with('success', 'Maintenance berhasil diupdate!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate maintenance: ' . $e->getMessage())
                ->withInput();
        }
    }
}