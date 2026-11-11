<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
                'garansi.keterangan',
                'aset.nama_aset',
                'server_by_id.nama_server as nama_server_by_id',
                'server_by_asset.nama_server as nama_server_by_asset',
            ]);

        $warranties->each(function ($warranty) {
            $warranty->status_garansi = $this->getWarrantyStatus($warranty->tanggal_berakhir);
        });

        return view('pages.aset.garansi.garansi', compact('warranties'));
    }

    public function create()
    {
        $assets = DB::table('aset')
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('garansi')
                    ->whereColumn('garansi.id_aset', 'aset.id_aset');
            })
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('server')
                    ->join('garansi', 'garansi.id_server', '=', 'server.id_server')
                    ->whereColumn('server.id_aset', 'aset.id_aset');
            })
            ->orderBy('nama_aset')
            ->get(['id_aset', 'nama_aset']);

        return view('pages.aset.garansi.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_aset' => [
                'required',
                'integer',
                'exists:aset,id_aset',
                function ($attribute, $value, $fail) {
                    $assetHasWarranty = DB::table('garansi')
                        ->where('id_aset', $value)
                        ->exists();
                    $serverHasWarranty = DB::table('server')
                        ->join('garansi', 'garansi.id_server', '=', 'server.id_server')
                        ->where('server.id_aset', $value)
                        ->exists();

                    if ($assetHasWarranty || $serverHasWarranty) {
                        $fail('Aset atau server ini sudah memiliki data garansi.');
                    }
                },
            ],
            'nomor_garansi' => 'nullable|string|max:200',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'tingkat_layanan' => 'nullable|string|max:100',
            'tingkat_sla' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:510',
        ]);

        DB::table('garansi')->insert([
            'id_aset' => $validated['id_aset'],
            'nomor_garansi' => $validated['nomor_garansi'] ?? null,
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_berakhir' => $validated['tanggal_berakhir'] ?? null,
            'tingkat_layanan' => $validated['tingkat_layanan'] ?? null,
            'tingkat_sla' => $validated['tingkat_sla'] ?? null,
            'status_garansi' => $this->getWarrantyStatus($validated['tanggal_berakhir'] ?? null),
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('aset.garansi')
            ->with('success', 'Data garansi berhasil ditambahkan.');
    }

    private function getWarrantyStatus(?string $endDate): string
    {
        return $endDate && Carbon::parse($endDate)->lt(Carbon::today())
            ? 'Berakhir'
            : 'Aktif';
    }
}
