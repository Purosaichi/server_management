<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StorageController extends Controller
{
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
