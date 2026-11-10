<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PenyediaController extends Controller
{
    public function create()
    {
        return view('pages.aset.penyedia-create');
    }

    public function edit(int $id)
    {
        $provider = $this->activeProviders()
            ->where('id_penyedia', $id)
            ->first();

        abort_if($provider === null, 404);

        return view('pages.aset.penyedia-edit', compact('provider'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProvider($request);

        DB::table('penyedia')->insert($validated);

        return redirect()->route('aset.penyedia')
            ->with('success', 'Data penyedia berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $provider = $this->activeProviders()
            ->where('id_penyedia', $id)
            ->first();

        abort_if($provider === null, 404);

        $validated = $this->validateProvider($request);
        $this->activeProviders()
            ->where('id_penyedia', $id)
            ->update($validated);

        return redirect()->route('aset.penyedia')
            ->with('success', 'Data penyedia berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        abort_unless(
            Schema::hasColumn('penyedia', 'deleted_at'),
            409,
            'Kolom deleted_at belum tersedia. Jalankan migration terlebih dahulu.'
        );

        $this->activeProviders()
            ->where('id_penyedia', $id)
            ->firstOrFail();

        DB::table('penyedia')
            ->where('id_penyedia', $id)
            ->update(['deleted_at' => now()]);

        return redirect()->route('aset.penyedia')
            ->with('success', 'Data penyedia berhasil dihapus.');
    }

    private function validateProvider(Request $request): array
    {
        return $request->validate([
            'nama_penyedia' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:50',
            'alamat_email' => 'nullable|email|max:255',
            'nama_kontak' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
    }

    public function index()
    {
        $providers = $this->activeProviders()
            ->orderBy('nama_penyedia')
            ->get([
                'id_penyedia',
                'nama_penyedia',
                'alamat',
                'nomor_telepon',
                'alamat_email',
                'nama_kontak',
                'keterangan',
            ]);

        return view('pages.aset.penyedia', compact('providers'));
    }

    private function activeProviders(): Builder
    {
        $query = DB::table('penyedia');

        if (Schema::hasColumn('penyedia', 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        return $query;
    }
}
