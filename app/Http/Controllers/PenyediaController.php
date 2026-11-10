<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PenyediaController extends Controller
{
    public function index()
    {
        $providers = DB::table('penyedia')
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
}
