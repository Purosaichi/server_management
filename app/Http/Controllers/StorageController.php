<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StorageController extends Controller
{
    public function create()
    {
        $assets = DB::table('aset')
            ->orderBy('nama_aset')
            ->get(['id_aset', 'kode_aset', 'nama_aset']);

        return view('pages.aset.storage.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_aset' => 'required|integer|exists:aset,id_aset',
            'jenis_storage' => 'nullable|string|max:50',
            'kapasitas_raw' => 'nullable|integer|min:0',
            'kapasitas_terpakai' => 'nullable|integer|min:0',
            'jenis_disk' => 'nullable|string|max:50',
            'jumlah_disk' => 'nullable|integer|min:0',
            'tingkat_raid' => 'nullable|string|max:10',
            'jumlah_pengendali' => 'nullable|integer|min:0',
            'protokol_storage' => 'nullable|string|max:50',
            'jumlah_volume' => 'nullable|integer|min:0',
            'jumlah_lun' => 'nullable|integer|min:0',
            'kapasitas_snapshot' => 'nullable|integer|min:0',
            'status_replikasi' => 'nullable|string|max:50',
            'alamat_ip_manajemen' => 'nullable|ip',
        ]);

        if (
            isset($validated['kapasitas_raw'], $validated['kapasitas_terpakai']) &&
            $validated['kapasitas_terpakai'] > $validated['kapasitas_raw']
        ) {
            return back()
                ->withErrors(['kapasitas_terpakai' => 'Kapasitas terpakai tidak boleh melebihi kapasitas raw.'])
                ->withInput();
        }

        $id = DB::table('storage')->insertGetId($validated);

        return redirect()->route('aset.storage.detail', $id)
            ->with('success', 'Data storage berhasil ditambahkan.');
    }

    public function index()
    {
        $storageQuery = $this->selectStorageData($this->storageQuery(), [
                'storage.id_storage',
                'storage.id_aset',
                'storage.jenis_storage',
                'storage.kapasitas_raw',
                'storage.kapasitas_terpakai',
                'aset.kode_aset',
                'aset.nama_aset',
            ])
            ->orderBy('storage.id_storage');

        $storageDevices = $storageQuery->get();

        return view('pages.aset.storage.storage', [
            'storageDevices' => $storageDevices,
            'storageCount' => $storageDevices->count(),
            'totalRawCapacity' => $storageDevices->sum('kapasitas_raw'),
            'totalUsedCapacity' => $storageDevices->sum('kapasitas_terpakai'),
            'totalAvailableCapacity' => $storageDevices->sum('kapasitas_tersedia'),
        ]);
    }

    public function show(int $id)
    {
        $storage = $this->selectStorageData(
            $this->storageQuery()->where('storage.id_storage', $id),
            [
            'storage.id_storage',
            'storage.id_aset',
            'storage.jenis_storage',
            'storage.kapasitas_raw',
            'storage.kapasitas_terpakai',
            'storage.jenis_disk',
            'storage.jumlah_disk',
            'storage.tingkat_raid',
            'storage.jumlah_pengendali',
            'storage.protokol_storage',
            'storage.jumlah_volume',
            'storage.jumlah_lun',
            'storage.kapasitas_snapshot',
            'storage.status_replikasi',
            'storage.alamat_ip_manajemen',
            'aset.kode_aset',
            'aset.nama_aset',
            ]
        )->first();

        abort_if($storage === null, 404);

        return view('pages.aset.storage.detail', compact('storage'));
    }

    private function storageQuery(): Builder
    {
        $query = DB::table('storage')
            ->leftJoin('aset', 'aset.id_aset', '=', 'storage.id_aset');

        if (Schema::hasColumn('storage', 'deleted_at')) {
            $query->whereNull('storage.deleted_at');
        }

        return $query;
    }

    private function selectStorageData(Builder $query, array $fields): Builder
    {
        return $query
            ->select($fields)
            ->selectRaw('storage.kapasitas_raw - storage.kapasitas_terpakai AS kapasitas_tersedia')
            ->selectRaw(
                'CASE WHEN storage.kapasitas_raw > 0 ' .
                'THEN CAST((storage.kapasitas_terpakai * 100.0) / storage.kapasitas_raw AS DECIMAL(9, 2)) ' .
                'ELSE 0 END AS persentase_penggunaan'
            );
    }
}
