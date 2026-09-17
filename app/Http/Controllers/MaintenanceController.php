<?php

namespace App\Http\Controllers;

class MaintenanceController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => 67,
            'completed' => 4,
            'in_progress' => 2,
            'scheduled' => 2,
            'period' => 'Agustus 2026'
        ];

        // Maintenance server
        $ServerMaintenance = [
            [
                'id' => 'MTN-2026-001',
                'target' => 'SVR-001',
                'target_sub' => 'Application Server',
                'jenis' => 'Update & Patch',
                'kategori' => 'Server',
                'jadwal' => '20 Agu 2026',
                'jam' => '10:00 - 11:00',
                'durasi' => '1 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'In Progress'
            ],

            [
                'id' => 'MTN-2026-002',
                'target' => 'SVR-002',
                'target_sub' => 'Database Server',
                'jenis' => 'Database Optimization',
                'kategori' => 'Server',
                'jadwal' => '22 Agu 2026',
                'jam' => '09:00 - 10:00',
                'durasi' => '1 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-003',
                'target' => 'SVR-003',
                'target_sub' => 'Application Server',
                'jenis' => 'Hardware Check',
                'kategori' => 'Server',
                'jadwal' => '25 Agu 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-004',
                'target' => 'SVR-004',
                'target_sub' => 'Database Server',
                'jenis' => 'Hardware Upgrade',
                'kategori' => 'Server',
                'jadwal' => '28 Agu 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-005',
                'target' => 'SVR-005',
                'target_sub' => 'Application Server',
                'jenis' => 'Server Cleanup',
                'kategori' => 'Server',
                'jadwal' => '30 Agu 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-006',
                'target' => 'SVR-006',
                'target_sub' => 'Database Server',
                'jenis' => 'Update & Patch',
                'kategori' => 'Server',
                'jadwal' => '02 Sep 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],
        ];

        // Maintenance aplikasi
        $ApplicationMaintenance =[
            [
                'id' => 'MTN-2026-001',
                'target' => 'GTK-Kemendikasmen',
                'target_sub' => 'Application',
                'jenis' => 'Database Optimization',
                'kategori' => 'Application',
                'jadwal' => '22 Agu 2026',
                'jam' => '09:00-10:00',
                'durasi' => '1 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-002',
                'target' => 'GTK-Kemendikasmen',
                'target_sub' => 'Application',
                'jenis' => 'Update & Patch',
                'kategori' => 'Application',
                'jadwal' => '25 Agu 2026',
                'jam' => '13:00-15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-003',
                'target' => 'GTK-Kemendikasmen',
                'target_sub' => 'Application',
                'jenis' => 'Server Cleanup',
                'kategori' => 'Application',
                'jadwal' => '28 Agu 2026',
                'jam' => '13:00-15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-001',
                'target' => 'GTK-Kemendikasmen',
                'target_sub' => 'Application',
                'jenis' => 'Database Optimization',
                'kategori' => 'Application',
                'jadwal' => '22 Agu 2026',
                'jam' => '09:00-10:00',
                'durasi' => '1 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-002',
                'target' => 'GTK-Kemendikasmen',
                'target_sub' => 'Application',
                'jenis' => 'Update & Patch',
                'kategori' => 'Application',
                'jadwal' => '25 Agu 2026',
                'jam' => '13:00-15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-003',
                'target' => 'GTK-Kemendikasmen',
                'target_sub' => 'Application',
                'jenis' => 'Server Cleanup',
                'kategori' => 'Application',
                'jadwal' => '28 Agu 2026',
                'jam' => '13:00-15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-001',
                'target' => 'GTK-Kemendikasmen',
                'target_sub' => 'Application',
                'jenis' => 'Database Optimization',
                'kategori' => 'Application',
                'jadwal' => '22 Agu 2026',
                'jam' => '09:00-10:00',
                'durasi' => '1 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-002',
                'target' => 'GTK-Kemendikasmen',
                'target_sub' => 'Application',
                'jenis' => 'Update & Patch',
                'kategori' => 'Application',
                'jadwal' => '25 Agu 2026',
                'jam' => '13:00-15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-003',
                'target' => 'GTK-Kemendikasmen',
                'target_sub' => 'Application',
                'jenis' => 'Server Cleanup',
                'kategori' => 'Application',
                'jadwal' => '28 Agu 2026',
                'jam' => '13:00-15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],
        ];

        // Maintenance domain
        $DomainMaintenance = [
            [
                 'id' => 'MTN-2026-001',
                'target' => 'WebGTK.com',
                'target_sub' => 'Domain',
                'jenis' => 'SSL Renewal',
                'kategori' => 'Domain',
                'jadwal' => '20 Agu 2026',
                'jam' => '10:00 - 11:00',
                'durasi' => '1 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-002',
                'target' => 'WebGTK.com',
                'target_sub' => 'Domain',
                'jenis' => 'DNS Update',
                'kategori' => 'Domain',
                'jadwal' => '22 Agu 2026',
                'jam' => '09:00 - 10:00',
                'durasi' => '1 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-003',
                'target' => 'WebGTK.com',
                'target_sub' => 'Domain',
                'jenis' => 'WHOIS Update',
                'kategori' => 'Domain',
                'jadwal' => '25 Agu 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-004',
                'target' => 'WebGTK.com',
                'target_sub' => 'Domain',
                'jenis' => 'Registrar Update',
                'kategori' => 'Domain',
                'jadwal' => '28 Agu 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-005',
                'target' => 'WebGTK.com',
                'target_sub' => 'Domain',
                'jenis' => 'DNSSEC Update',
                'kategori' => 'Domain',
                'jadwal' => '30 Agu 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-006',
                'target' => 'WebGTK.com',
                'target_sub' => 'Domain',
                'jenis' => 'Registrar Update',
                'kategori' => 'Domain',
                'jadwal' => '02 Sep 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-007',
                'target' => 'WebGTK.com',
                'target_sub' => 'Domain',
                'jenis' => 'SSL Renewal',
                'kategori' => 'Domain',
                'jadwal' => '05 Sep 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-008',
                'target' => 'WebGTK.com',
                'target_sub' => 'Domain',
                'jenis' => 'DNS Update',
                'kategori' => 'Domain',
                'jadwal' => '08 Sep 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-009',
                'target' => 'WebGTK.com',
                'target_sub' => 'Domain',
                'jenis' => 'WHOIS Update',
                'kategori' => 'Domain',
                'jadwal' => '11 Sep 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-010',
                'target' => 'WebGTK.com',
                'target_sub' => 'Domain',
                'jenis' => 'DNSSEC Update',
                'kategori' => 'Domain',
                'jadwal' => '12 Sep 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-011',
                'target' => 'WebGTK.com',
                'target_sub' => 'Domain',
                'jenis' => 'Registrar Update',
                'kategori' => 'Domain',
                'jadwal' => '14 Sep 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],

            [
                'id' => 'MTN-2026-012',
                'target' => 'WebGTK.com',
                'target_sub' => 'Domain',
                'jenis' => 'SSL Renewal',
                'kategori' => 'Domain',
                'jadwal' => '17 Sep 2026',
                'jam' => '13:00 - 15:00',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll',
                'status' => 'Scheduled'
            ],
        ];

        // Data maintenance
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

        return view('pages.maintenance.maintenance', compact(
            'stats',
            'maintenances',
            'ServerMaintenance',
            'ApplicationMaintenance',
            'DomainMaintenance'
        ))->with([
            'serverMaintenances' => $ServerMaintenance,
            'applicationMaintenances' => $ApplicationMaintenance,
            'domainMaintenances' => $DomainMaintenance,
        ]);
    }
}