<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Aset;

class ServerController extends Controller
{
    public function index()
    {
        // Ambil data server + relasi aset
        $servers = Server::with('aset')->get()->map(function ($server) {
            return [
                'id' => $server->id_server,
                'name' => $server->aset->nama_aset ?? 'SVR-' . $server->id_server,
                'ip' => $server->alamat_ip_manajemen ?? '-',
                'status' => $server->aset->status_aset ?? 'Offline',
                'cpu' => $server->jumlah_prosessor ? $server->jumlah_prosessor . ' Core' : '-',
                'ram' => $server->kapasitas_memori ? $server->kapasitas_memori . ' GB' : '-',
                'disk' => $server->kapasitas_penyimpanan ? $server->kapasitas_penyimpanan . ' GB' : '-',
                'uptime' => '-', 
            ];
        });

        return view('pages.server.server', compact('servers'));
    }

    public function show(int $id)
    {
        // detail server
        $server = Server::with('aset')->find($id);

        abort_unless($server, 404);

        // Format data 
        $serverData = [
            'id' => $server->id_server,
            'name' => $server->aset->nama_aset ?? 'SVR-' . $server->id_server,
            'ip' => $server->alamat_ip_manajemen ?? '-',
            'ip_produksi' => $server->alamat_ip_produksi ?? '-',
            'status' => $server->aset->status_aset ?? 'Offline',
            'cpu' => $server->jumlah_prosessor ? $server->jumlah_prosessor . ' Core' : '-',
            'ram' => $server->kapasitas_memori ? $server->kapasitas_memori . ' GB' : '-',
            'disk' => $server->kapasitas_penyimpanan ? $server->kapasitas_penyimpanan . ' GB' : '-',
            'uptime' => '-',
            'hostname' => $server->nama_cluster ?? strtolower(str_replace(' ', '-', $server->aset->nama_aset ?? 'server')),
            'os' => $server->sistem_operasi ?? '-',
            'location' => 'Data Center Kemendikdasmen',
            'last_check' => now()->format('d F Y, H:i'),
        ];

        return view('pages.server.detail', ['server' => $serverData]);
    }
}