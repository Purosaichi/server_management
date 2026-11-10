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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_merek' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        DB::table('merek')->insert($validated);

        return redirect()->route('aset.merek')
            ->with('success', 'Data merek berhasil ditambahkan.');
    }

    public function index()
    {
        $brands = DB::table('merek')
            ->whereNull('deleted_at')
            ->orderBy('nama_merek')
            ->get(['id_merek', 'nama_merek', 'keterangan']);

        return view('pages.aset.merek', compact('brands'));
    }
}
