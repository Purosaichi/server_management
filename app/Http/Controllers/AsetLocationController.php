<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsetLocationController extends Controller
{
    public function index()
    {
        $locations = DB::table('lokasi_aset')
            ->leftJoin('data_center', 'data_center.id_data_center', '=', 'lokasi_aset.id_data_center')
            ->whereNull('lokasi_aset.deleted_at')
            ->orderBy('lokasi_aset.id_lokasi_aset')
            ->get([
                'data_center.nama_data_center',
                'lokasi_aset.nama_gedung',
                'lokasi_aset.lantai',
                'lokasi_aset.nama_ruangan',
                'lokasi_aset.zona',
                'lokasi_aset.kode_rak',
                'lokasi_aset.nama_rak',
                'lokasi_aset.tinggi_rak',
                'lokasi_aset.posisi_rak',
                'lokasi_aset.tanggal_penempatan',
                'lokasi_aset.keterangan',
            ]);

        return view('pages.aset.lokasi.lokasi', compact('locations'));
    }

    public function dataCenters()
    {
        $dataCenters = DB::table('data_center')
            ->whereNull('deleted_at')
            ->orderBy('nama_data_center')
            ->get(['nama_data_center', 'lokasi', 'tipe_dc', 'keterangan']);

        return view('pages.aset.lokasi.data-centers', compact('dataCenters'));
    }

    public function createDataCenter()
    {
        return view('pages.aset.lokasi.data-centers-create');
    }

    public function storeDataCenter(Request $request)
    {
        $validated = $request->validate([
            'nama_data_center' => 'required|string|max:200',
            'lokasi' => 'nullable|string|max:300',
            'tipe_dc' => 'nullable|in:Primary,DRP,Cloud',
            'keterangan' => 'nullable|string|max:510',
        ]);

        DB::table('data_center')->insert($validated);

        return redirect()->route('aset.data-centers')->with('success', 'Data Center berhasil ditambahkan.');
    }

    public function create()
    {
        $assets = DB::table('aset')
            ->orderBy('nama_aset')
            ->get(['id_aset', 'kode_aset', 'nama_aset']);
        $dataCenters = DB::table('data_center')
            ->whereNull('deleted_at')
            ->orderBy('nama_data_center')
            ->get(['id_data_center', 'nama_data_center', 'lokasi']);

        return view('pages.aset.lokasi.create', compact('assets', 'dataCenters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_aset' => 'required|integer|exists:aset,id_aset',
            'id_data_center' => 'nullable|integer|exists:data_center,id_data_center',
            'nama_gedung' => 'nullable|string|max:200',
            'lantai' => 'nullable|string|max:40',
            'nama_ruangan' => 'nullable|string|max:200',
            'zona' => 'nullable|string|max:100',
            'kode_rak' => 'nullable|string|max:100',
            'nama_rak' => 'nullable|string|max:200',
            'tinggi_rak' => 'nullable|integer|min:0',
            'posisi_rak' => 'nullable|string|max:100',
            'tanggal_penempatan' => 'nullable|date',
            'keterangan' => 'nullable|string|max:510',
        ]);

        DB::table('lokasi_aset')->insert($validated);

        return redirect()->route('aset.lokasi')->with('success', 'Lokasi aset berhasil ditambahkan.');
    }
}