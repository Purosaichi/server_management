<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MerekController extends Controller
{
    public function index()
    {
        $brands = DB::table('merek')
            ->whereNull('deleted_at')
            ->orderBy('nama_merek')
            ->get(['id_merek', 'nama_merek', 'keterangan']);

        return view('pages.aset.merek', compact('brands'));
    }
}
