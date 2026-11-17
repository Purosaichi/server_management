<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AsetDashboardController extends Controller
{
    public function index()
    {
        $servers = DB::table('server')
            ->whereNull('deleted_at')
            ->orderByDesc('id_server')
            ->limit(3)
            ->get(['nama_server']);
        $serverCount = DB::table('server')->whereNull('deleted_at')->count();

        $routerCount = DB::table('router')->count();
        $switchCount = DB::table('switch')->count();

        $locations = DB::table('lokasi_aset')
            ->leftJoin('data_center', 'data_center.id_data_center', '=', 'lokasi_aset.id_data_center')
            ->whereNull('lokasi_aset.deleted_at')
            ->orderBy('data_center.nama_data_center')
            ->orderBy('lokasi_aset.nama_gedung')
            ->limit(3)
            ->get([
                'data_center.nama_data_center',
                'lokasi_aset.nama_gedung',
                'lokasi_aset.nama_rak',
            ]);
        $locationCount = DB::table('lokasi_aset')->whereNull('deleted_at')->count();

        $storageQuery = DB::table('storage');
        if (Schema::hasColumn('storage', 'deleted_at')) {
            $storageQuery->whereNull('deleted_at');
        }
        $storageCount = (clone $storageQuery)->count();
        $storageRawCapacity = (clone $storageQuery)->sum('kapasitas_raw');
        $storageUsedCapacity = (clone $storageQuery)->sum('kapasitas_terpakai');
        $storageDevices = (clone $storageQuery)
            ->orderByDesc('id_storage')
            ->limit(3)
            ->get(['jenis_storage', 'kapasitas_raw', 'kapasitas_terpakai']);

        $providersQuery = DB::table('penyedia');
        if (Schema::hasColumn('penyedia', 'deleted_at')) {
            $providersQuery->whereNull('deleted_at');
        }
        $providerCount = (clone $providersQuery)->count();
        $providers = (clone $providersQuery)
            ->orderBy('nama_penyedia')
            ->limit(3)
            ->pluck('nama_penyedia');

        $brands = DB::table('merek')
            ->whereNull('deleted_at')
            ->orderBy('nama_merek')
            ->limit(3)
            ->pluck('nama_merek');
        $brandCount = DB::table('merek')->whereNull('deleted_at')->count();

        $warranties = DB::table('garansi')
            ->orderByDesc('tanggal_berakhir')
            ->limit(3)
            ->get(['nomor_garansi', 'tanggal_berakhir']);
        $warrantyCount = DB::table('garansi')->count();

        $sections = [
            [
                'title' => 'Server',
                'icon' => 'fa-server',
                'url' => route('server.index'),
                'count' => $serverCount,
                'summary' => 'server terdaftar',
                'items' => $servers->map(fn ($server) => [
                    'name' => $server->nama_server ?: 'Server tanpa nama',
                    'detail' => 'Server',
                ]),
            ],
            [
                'title' => 'Network Device',
                'icon' => 'fa-network-wired',
                'url' => route('network-device.router'),
                'count' => $routerCount + $switchCount,
                'summary' => "{$routerCount} router · {$switchCount} switch",
                'items' => collect([
                    ['name' => 'Router', 'detail' => "{$routerCount} perangkat"],
                    ['name' => 'Switch', 'detail' => "{$switchCount} perangkat"],
                ]),
            ],
            [
                'title' => 'Lokasi Aset',
                'icon' => 'fa-location-dot',
                'url' => route('aset.lokasi'),
                'count' => $locationCount,
                'summary' => 'lokasi terdaftar',
                'items' => $locations->map(fn ($location) => [
                    'name' => collect([
                        $location->nama_data_center,
                        $location->nama_gedung,
                        $location->nama_rak,
                    ])->filter()->implode(' · ') ?: 'Lokasi tanpa rincian',
                    'detail' => 'Lokasi aset',
                ]),
            ],
            [
                'title' => 'Storage',
                'icon' => 'fa-hard-drive',
                'url' => route('aset.storage'),
                'count' => $storageCount,
                'summary' => number_format((float) $storageRawCapacity, 0, ',', '.') . ' kapasitas · '
                    . number_format((float) $storageUsedCapacity, 0, ',', '.') . ' terpakai',
                'items' => $storageDevices->map(fn ($storage) => [
                    'name' => $storage->jenis_storage ?: 'Storage',
                    'detail' => number_format((float) $storage->kapasitas_terpakai, 0, ',', '.') . ' terpakai',
                ]),
            ],
            [
                'title' => 'Penyedia',
                'icon' => 'fa-user',
                'url' => route('aset.penyedia'),
                'count' => $providerCount,
                'summary' => 'penyedia terdaftar',
                'items' => $providers->map(fn ($provider) => [
                    'name' => $provider,
                    'detail' => 'Penyedia',
                ]),
            ],
            [
                'title' => 'Merek',
                'icon' => 'fa-tag',
                'url' => route('aset.merek'),
                'count' => $brandCount,
                'summary' => 'merek terdaftar',
                'items' => $brands->map(fn ($brand) => [
                    'name' => $brand,
                    'detail' => 'Merek aset',
                ]),
            ],
            [
                'title' => 'Garansi',
                'icon' => 'fa-shield-halved',
                'url' => route('aset.garansi'),
                'count' => $warrantyCount,
                'summary' => 'garansi tercatat',
                'items' => $warranties->map(fn ($warranty) => [
                    'name' => $warranty->nomor_garansi ?: 'Garansi tanpa nomor',
                    'detail' => $warranty->tanggal_berakhir
                        ? 'Berakhir ' . date('d/m/Y', strtotime($warranty->tanggal_berakhir))
                        : 'Tanggal berakhir belum diisi',
                ]),
            ],
        ];

        return view('pages.aset.aset', compact('sections'));
    }
}
