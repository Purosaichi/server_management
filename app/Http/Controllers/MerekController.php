<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MerekController extends Controller
{
    public function create()
    {
        return view('pages.aset.merek-create');
    }

    public function edit(int $id)
    {
        $brand = $this->activeBrands()
            ->where('id_merek', $id)
            ->first();

        abort_if($brand === null, 404);

        return view('pages.aset.merek-edit', compact('brand'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateBrand($request);

        DB::table('merek')->insert($validated);

        return redirect()->route('aset.merek')
            ->with('success', 'Data merek berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $brand = $this->activeBrands()
            ->where('id_merek', $id)
            ->first();

        abort_if($brand === null, 404);

        $validated = $this->validateBrand($request);
        $this->activeBrands()
            ->where('id_merek', $id)
            ->update($validated);

        return redirect()->route('aset.merek')
            ->with('success', 'Data merek berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->activeBrands()
            ->where('id_merek', $id)
            ->firstOrFail();

        DB::table('merek')
            ->where('id_merek', $id)
            ->whereNull('deleted_at')
            ->update(['deleted_at' => now()]);

        return redirect()->route('aset.merek')
            ->with('success', 'Data merek berhasil dihapus.');
    }

    private function validateBrand(Request $request): array
    {
        return $request->validate([
            'nama_merek' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
    }

    public function index()
    {
        $brands = $this->activeBrands()
            ->orderBy('nama_merek')
            ->get(['id_merek', 'nama_merek', 'keterangan']);

        return view('pages.aset.merek', compact('brands'));
    }

    private function activeBrands()
    {
        return DB::table('merek')->whereNull('deleted_at');
    }
}
