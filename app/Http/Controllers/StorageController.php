<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StorageController extends Controller
{
    public function index()
    {
        $storageQuery = DB::table('storage')
            ->leftJoin('aset', 'aset.id_aset', '=', 'storage.id_aset')
            ->orderBy('storage.id_storage');

        if (Schema::hasColumn('storage', 'deleted_at')) {
            $storageQuery->whereNull('storage.deleted_at');
        }

        $storageDevices = $storageQuery->get([
                'storage.id_storage',
                'storage.id_aset',
                'storage.jenis_storage',
                'storage.kapasitas_raw',
                'storage.kapasitas_terpakai',
                'storage.kapasitas_tersedia',
                'storage.persentase_penggunaan',
                'aset.kode_aset',
                'aset.nama_aset',
            ]);

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
        $storageQuery = DB::table('storage')
            ->leftJoin('aset', 'aset.id_aset', '=', 'storage.id_aset')
            ->where('storage.id_storage', $id);

        if (Schema::hasColumn('storage', 'deleted_at')) {
            $storageQuery->whereNull('storage.deleted_at');
        }

        $storage = $storageQuery->first([
            'storage.id_storage',
            'storage.id_aset',
            'storage.jenis_storage',
            'storage.kapasitas_raw',
            'storage.kapasitas_terpakai',
            'storage.kapasitas_tersedia',
            'storage.persentase_penggunaan',
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
        ]);

        abort_if($storage === null, 404);

        return view('pages.aset.storage.detail', compact('storage'));
    }
}
