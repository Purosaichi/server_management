<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class WarrantyController extends Controller
{
    public function index()
    {
        $warranties = DB::table('garansi')
            ->leftJoin('aset', 'aset.id_aset', '=', 'garansi.id_aset')
            ->leftJoin('server as server_by_id', 'server_by_id.id_server', '=', 'garansi.id_server')
            ->leftJoin('server as server_by_asset', 'server_by_asset.id_aset', '=', 'garansi.id_aset')
            ->orderByDesc('garansi.tanggal_berakhir')
            ->get([
                'garansi.id_garansi',
                'garansi.nomor_garansi',
                'garansi.tanggal_mulai',
                'garansi.tanggal_berakhir',
                'garansi.tingkat_layanan',
                'garansi.tingkat_sla',
                'garansi.status_garansi',
                'garansi.keterangan',
                'aset.nama_aset',
                'server_by_id.nama_server as nama_server_by_id',
                'server_by_asset.nama_server as nama_server_by_asset',
            ]);

        return view('pages.aset.garansi', compact('warranties'));
    }
}
