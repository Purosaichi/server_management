<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AsetLocationController extends Controller
{
    public function index()
    {
        $locations = DB::table('lokasi_aset')
            ->orderBy('id_lokasi_aset')
            ->get([
                'id_lokasi_aset',
                'id_aset',
                'nama_data_center',
                'nama_gedung',
                'lantai',
                'nama_ruangan',
                'zona',
                'kode_rak',
                'nama_rak',
                'tinggi_rak',
                'posisi_rak',
                'tanggal_penempatan',
                'keterangan',
            ]);

        return view('pages.aset.lokasi.lokasi', compact('locations'));
    }
}