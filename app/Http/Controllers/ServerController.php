<?php

namespace App\Http\Controllers;

class ServerController extends Controller
{
    public function index()
    {
        $servers = $this->servers();

        return view('pages.server.server', compact('servers'));
    }

    public function show(int $id)
    {
        $server = collect($this->servers())->firstWhere('id', $id);

        abort_unless($server, 404);

        $server += [
            'hostname' => strtolower(str_replace(' ', '-', $server['name'])),
            'os' => 'Linux Ubuntu 22.04',
            'location' => 'Data Center Kemendikdasmen',
            'last_check' => '08 September 2026, 10:30',
        ];

        return view('pages.server.detail', compact('server'));
    }

    private function servers(): array
    {
        return [
            ['id' => 1, 'name' => 'SV-R - 001', 'ip' => '103.231.3.01', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
            ['id' => 2, 'name' => 'SV-R - 002', 'ip' => '103.231.3.02', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '12d 22h'],
            ['id' => 3, 'name' => 'SV-R - 003', 'ip' => '103.231.3.03', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '9d 11h'],
            ['id' => 4, 'name' => 'SV-R - 004', 'ip' => '103.231.3.04', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '32d 1h'],
            ['id' => 5, 'name' => 'SV-R - 005', 'ip' => '103.231.3.05', 'status' => 'Offline', 'cpu' => '---', 'ram' => '---', 'disk' => '---', 'uptime' => '---'],
            ['id' => 6, 'name' => 'SV-R - 006', 'ip' => '103.231.3.06', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '9d 11h'],
        ];
    }
}
