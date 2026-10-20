<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServerNoteController extends Controller
{
    /**
     * Simpan catatan baru
     * Kalo ada catatan lama, soft delete dulu
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_server' => 'required|integer|exists:server,id_server',
            'catatan'   => 'required|string',
            'id_pic'    => 'nullable|integer|exists:pic,id_pic',
        ]);

        try {
            DB::transaction(function () use ($request) {
                /* Soft delete catatan lama (biar cuma 1 yang aktif) */
                DB::table('server_notes')
                    ->where('id_server', $request->id_server)
                    ->whereNull('deleted_at')
                    ->update(['deleted_at' => now()]);

                /* Insert catatan baru */
                DB::table('server_notes')->insert([
                    'id_server'   => $request->id_server,
                    'catatan'     => $request->catatan,
                    'id_pic'      => $request->id_pic,
                    'dibuat_pada' => now(),
                ]);
            });

            return redirect()->back()->with('success', 'Catatan berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan catatan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update catatan
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'catatan' => 'required|string',
            'id_pic'  => 'nullable|integer|exists:pic,id_pic',
        ]);

        try {
            DB::table('server_notes')
                ->where('id_note', $id)
                ->update([
                    'catatan'         => $request->catatan,
                    'id_pic'          => $request->id_pic,
                    'diperbarui_pada' => now(),
                ]);

            return redirect()->back()->with('success', 'Catatan berhasil diupdate!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate catatan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Soft delete catatan
     */
    public function destroy(int $id)
    {
        try {
            DB::table('server_notes')
                ->where('id_note', $id)
                ->update(['deleted_at' => now()]);

            return redirect()->back()->with('success', 'Catatan berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus catatan: ' . $e->getMessage());
        }
    }
}