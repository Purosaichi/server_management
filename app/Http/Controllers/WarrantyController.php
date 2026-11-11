<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        $assets = $this->getAvailableAssets();

        return view('pages.aset.garansi.create', compact('assets'));
    }

    public function edit(int $id)
    {
        $warranty = DB::table('garansi')->where('id_garansi', $id)->firstOrFail();
        $assets = $this->getAvailableAssets($id);

        return view('pages.aset.garansi.edit', compact('warranty', 'assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_aset' => ['required', 'integer', 'exists:aset,id_aset', Rule::unique('garansi', 'id_aset')],
            'nomor_garansi' => 'nullable|string|max:200',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'tingkat_layanan' => 'nullable|string|max:100',
            'tingkat_sla' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:510',
        ]);

        if ($this->serverHasWarranty($validated['id_aset'])) {
            return back()->withInput()->withErrors([
                'id_aset' => 'Server pada aset ini sudah memiliki data garansi.',
            ]);
        }

        DB::table('garansi')->insert($this->warrantyData($validated));

        return redirect()->route('aset.garansi')
            ->with('success', 'Data garansi berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $warranty = DB::table('garansi')->where('id_garansi', $id)->firstOrFail();
        $validated = $request->validate([
            'id_aset' => [
                'required',
                'integer',
                'exists:aset,id_aset',
                Rule::unique('garansi', 'id_aset')->ignore($id, 'id_garansi'),
            ],
            'nomor_garansi' => 'nullable|string|max:200',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'tingkat_layanan' => 'nullable|string|max:100',
            'tingkat_sla' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:510',
        ]);

        if ($this->serverHasWarranty($validated['id_aset'], $id)) {
            return back()->withInput()->withErrors([
                'id_aset' => 'Server pada aset ini sudah memiliki data garansi.',
            ]);
        }

        DB::table('garansi')->where('id_garansi', $id)->update($this->warrantyData($validated));

        return redirect()->route('aset.garansi')
            ->with('success', 'Data garansi berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        DB::table('garansi')->where('id_garansi', $id)->delete();

        return redirect()->route('aset.garansi')
            ->with('success', 'Data garansi berhasil dihapus.');
    }

    private function getAvailableAssets(?int $ignoreWarrantyId = null)
    {
        return DB::table('aset')
            ->whereNotExists(function ($query) use ($ignoreWarrantyId) {
                $query->selectRaw('1')
                    ->from('garansi')
                    ->whereColumn('garansi.id_aset', 'aset.id_aset')
                    ->when($ignoreWarrantyId, function ($query) use ($ignoreWarrantyId) {
                        $query->where('garansi.id_garansi', '!=', $ignoreWarrantyId);
                    });
            })
            ->whereNotExists(function ($query) use ($ignoreWarrantyId) {
                $query->selectRaw('1')
                    ->from('server')
                    ->join('garansi', 'garansi.id_server', '=', 'server.id_server')
                    ->whereColumn('server.id_aset', 'aset.id_aset')
                    ->when($ignoreWarrantyId, function ($query) use ($ignoreWarrantyId) {
                        $query->where('garansi.id_garansi', '!=', $ignoreWarrantyId);
                    });
            })
            ->orderBy('nama_aset')
            ->get(['id_aset', 'nama_aset']);
    }

    private function serverHasWarranty(int $assetId, ?int $ignoreWarrantyId = null): bool
    {
        return DB::table('server')
            ->join('garansi', 'garansi.id_server', '=', 'server.id_server')
            ->where('server.id_aset', $assetId)
            ->when($ignoreWarrantyId, function ($query) use ($ignoreWarrantyId) {
                $query->where('garansi.id_garansi', '!=', $ignoreWarrantyId);
            })
            ->exists();
    }

    private function warrantyData(array $validated): array
    {
        return [
            'id_aset' => $validated['id_aset'],
            'id_server' => null,
            'nomor_garansi' => $validated['nomor_garansi'] ?? null,
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_berakhir' => $validated['tanggal_berakhir'] ?? null,
            'tingkat_layanan' => $validated['tingkat_layanan'] ?? null,
            'tingkat_sla' => $validated['tingkat_sla'] ?? null,
            'status_garansi' => $this->getWarrantyStatus($validated['tanggal_berakhir'] ?? null),
            'keterangan' => $validated['keterangan'] ?? null,
        ];
    }

    private function getWarrantyStatus(?string $endDate): string
    {
        return $endDate && Carbon::parse($endDate)->lt(Carbon::today())
            ? 'Berakhir'
            : 'Aktif';
    }
}
