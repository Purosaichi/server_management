<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        //data statistik
        $stats = [
            'total' => 67,
            'completed' => 4,
            'in_progress' => 2,
            'scheduled' => 2,
            'period' => 'Agustus 2026'
        ];

        //data maintenance
        $maintenances = [
            [
                'id' => 'MTN-2026-001',
                'target' => 'SVR-001',
                'jenis' => 'Update & Patch',
                'kategori' => 'Application Server',
                'jadwal' => '20 Agu 2026',
                'jam' => '09:00 - 10:00',
                'durasi' => '1 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'In Progress'
            ],
            [
                'id' => 'MTN-2026-002',
                'target' => 'GTK-Kemendikasmen',
                'jenis' => 'Database Optimization',
                'kategori' => 'Application',
                'jadwal' => '22 Agu 2026',
                'jam' => '09:00 - 10:00',
                'durasi' => '1 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],
            [
                'id' => 'MTN-2026-004',
                'target' => 'SVR-012',
                'jenis' => 'Hardware Check',
                'kategori' => 'Database Server',
                'jadwal' => '10 Nov 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],
            [
                'id' => 'MTN-2026-005',
                'target' => 'SVR-020',
                'jenis' => 'Hardware Upgrade',
                'kategori' => 'Database Server',
                'jadwal' => '15 Nov 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],
            [
                'id' => 'MTN-2026-006',
                'target' => 'SVR-004',
                'jenis' => 'Server Cleanup',
                'kategori' => 'Database Server',
                'jadwal' => '18 Nov 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],
        ];

        return view('pages.maintenance.maintenance', compact('stats', 'maintenances'));
    }
}