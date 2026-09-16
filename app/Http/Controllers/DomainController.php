<?php

namespace App\Http\Controllers;

class DomainController extends Controller
{
    public function index()
    {
        $domains = [
            ['domain' => 'WebGTK-01.com', 'application' => 'App GTK 01', 'status' => 'Nonaktif', 'pic' => 'Seseorang', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
            ['domain' => 'WebGTK-02.com', 'application' => 'App GTK 02', 'status' => 'Aktif', 'pic' => 'Seseorang', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
            ['domain' => 'WebGTK-03.com', 'application' => 'App GTK 03', 'status' => 'Aktif', 'pic' => 'Seseorang', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
            ['domain' => 'WebGTK-04.com', 'application' => 'App GTK 04', 'status' => 'Aktif', 'pic' => 'Seseorang', 'ssl' => 'Nonaktif', 'last_activity' => '20 Agustus 2026'],
            ['domain' => 'WebGTK-05.com', 'application' => 'App GTK 05', 'status' => 'Aktif', 'pic' => 'Seseorang', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
            ['domain' => 'WebGTK-06.com', 'application' => 'App GTK 06', 'status' => 'Nonaktif', 'pic' => 'Seseorang', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
            ['domain' => 'WebGTK-07.com', 'application' => 'App GTK 07', 'status' => 'Aktif', 'pic' => 'Seseorang', 'ssl' => 'Nonaktif  ', 'last_activity' => '20 Agustus 2026'],
            ['domain' => 'WebGTK-08.com', 'application' => 'App GTK 08', 'status' => 'Aktif', 'pic' => 'Seseorang', 'ssl' => 'Nonaktif', 'last_activity' => '20 Agustus 2026'],
            ['domain' => 'WebGTK-09.com', 'application' => 'App GTK 09', 'status' => 'Aktif', 'pic' => 'Seseorang', 'ssl' => 'Aktif', 'last_activity' => '20 Agustus 2026'],
        ];

        return view('pages.domain.domain', compact('domains'));
    }
    

    public function show($id)
    {
        $domain = [
            'id' => $id,
            'domain' => 'WebGTK-0' . $id . '.com',
            'application' => 'App GTK 0' . $id,
            'status' => 'Aktif',
            'pic' => 'Seseorang',
            'ssl' => 'Aktif',
            'last_activity' => '20 Agustus 2026',
            'created_at' => '15 Januari 2023',
            'expired_at' => '20 Agustus 2026',
            'registrar' => 'Domain Registrar Indonesia',
            'nameserver' => 'ns1.webgtk.com',
        ];
        
        return view('pages.domain.detail', compact('domain'));
    }
}        