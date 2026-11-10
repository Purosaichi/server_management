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
                'lokasi_aset.id_lokasi_aset',
                'data_center.nama_data_center',
                'lokasi_aset.nama_gedung',
                'lokasi_aset.nama_ruangan',
                'lokasi_aset.zona',
                'lokasi_aset.nama_rak',
            ]);

        return view('pages.aset.lokasi.lokasi', compact('locations'));
    }

    public function show(int $id)
    {
        $location = DB::table('lokasi_aset')
            ->leftJoin('aset', 'aset.id_aset', '=', 'lokasi_aset.id_aset')
            ->leftJoin('data_center', 'data_center.id_data_center', '=', 'lokasi_aset.id_data_center')
            ->where('lokasi_aset.id_lokasi_aset', $id)
            ->whereNull('lokasi_aset.deleted_at')
            ->first([
                'lokasi_aset.id_lokasi_aset',
                'aset.kode_aset',
                'aset.nama_aset',
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

        abort_if($location === null, 404);

        return view('pages.aset.lokasi.detail', compact('location'));
    }

    public function dataCenters()
    {
        $dataCenters = DB::table('data_center')
            ->whereNull('deleted_at')
            ->orderBy('nama_data_center')
            ->get(['id_data_center', 'nama_data_center', 'lokasi', 'tipe_dc', 'keterangan']);

        return view('pages.aset.lokasi.data-centers', compact('dataCenters'));
    }

    public function editDataCenter(int $id)
    {
        $dataCenter = DB::table('data_center')
            ->where('id_data_center', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('pages.aset.lokasi.data-centers-edit', compact('dataCenter'));
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

    public function updateDataCenter(Request $request, int $id)
    {
        $validated = $request->validate([
            'nama_data_center' => 'required|string|max:200',
            'lokasi' => 'nullable|string|max:300',
            'tipe_dc' => 'nullable|in:Primary,DRP,Cloud',
            'keterangan' => 'nullable|string|max:510',
        ]);

        $updated = DB::table('data_center')
            ->where('id_data_center', $id)
            ->whereNull('deleted_at')
            ->update($validated);

        if ($updated === 0 && ! DB::table('data_center')
            ->where('id_data_center', $id)
            ->whereNull('deleted_at')
            ->exists()) {
            abort(404);
        }

        return redirect()->route('aset.data-centers')->with('success', 'Data Center berhasil diperbarui.');
    }

    public function destroyDataCenter(int $id)
    {
        DB::table('data_center')
            ->where('id_data_center', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        DB::table('data_center')
            ->where('id_data_center', $id)
            ->update(['deleted_at' => now()]);

        return redirect()->route('aset.data-centers')->with('success', 'Data Center berhasil dihapus.');
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

    public function edit(int $id)
    {
        $location = DB::table('lokasi_aset')
            ->where('id_lokasi_aset', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();
        $assets = DB::table('aset')
            ->orderBy('nama_aset')
            ->get(['id_aset', 'kode_aset', 'nama_aset']);
        $dataCenters = DB::table('data_center')
            ->whereNull('deleted_at')
            ->orderBy('nama_data_center')
            ->get(['id_data_center', 'nama_data_center', 'lokasi']);

        return view('pages.aset.lokasi.edit', compact('location', 'assets', 'dataCenters'));
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

    public function update(Request $request, int $id)
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

        $updated = DB::table('lokasi_aset')
            ->where('id_lokasi_aset', $id)
            ->whereNull('deleted_at')
            ->update($validated);

        if ($updated === 0 && ! DB::table('lokasi_aset')
            ->where('id_lokasi_aset', $id)
            ->whereNull('deleted_at')
            ->exists()) {
            abort(404);
        }

        return redirect()->route('aset.lokasi')->with('success', 'Lokasi aset berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $location = DB::table('lokasi_aset')
            ->where('id_lokasi_aset', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        DB::table('lokasi_aset')
            ->where('id_lokasi_aset', $location->id_lokasi_aset)
            ->update(['deleted_at' => now()]);

        return redirect()->route('aset.lokasi')->with('success', 'Lokasi aset berhasil dihapus.');
    }
}