<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        // ===== STATISTIK =====
        $stats = [
            'total' => 20,
            'critical' => 5,
            'warning' => 10,
            'info' => 5,
        ];

        // ===== DATA ALERT - SERVER =====
        $serverAlerts = [
            [
                'waktu' => '20 Agu 2026',
                'jam' => '10:00 - 11:00',
                'level' => 'Critical',
                'target' => 'SVR-001',
                'target_sub' => 'Application Server',
                'type' => 'Server',
                'deskripsi' => 'CPU usage di atas 90%',
                'keterangan' => 'Current: 92%',
                'status' => 'Aktif',
                'durasi' => '1 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '15 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Info',
                'target' => 'SVR-020',
                'target_sub' => 'Database Server',
                'type' => 'Server',
                'deskripsi' => 'Memory usage mencapai 95%',
                'keterangan' => 'Current: 94%',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '15 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Info',
                'target' => 'SVR-020',
                'target_sub' => 'Database Server',
                'type' => 'Server',
                'deskripsi' => 'Memory usage mencapai 95%',
                'keterangan' => 'Current: 94%',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '10 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Warning',
                'target' => 'SVR-012',
                'target_sub' => 'Database Server',
                'type' => 'Server',
                'deskripsi' => 'Storage Penuh',
                'keterangan' => 'Space tersisa tinggal 200 GB',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '15 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Info',
                'target' => 'SVR-020',
                'target_sub' => 'Database Server',
                'type' => 'Server',
                'deskripsi' => 'Memory usage mencapai 95%',
                'keterangan' => 'Current: 94%',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '18 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Info',
                'target' => 'SVR-004',
                'target_sub' => 'Database Server',
                'type' => 'Server',
                'deskripsi' => 'Disk usage mencapai 98%',
                'keterangan' => 'Current: 90%',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
        ];

        // ===== DATA ALERT - APPLICATION =====
        $applicationAlerts = [
            [
                'waktu' => '20 Agu 2026',
                'jam' => '10:00 - 11:00',
                'level' => 'Critical',
                'target' => 'App-001',
                'target_sub' => 'App Kemendikdasmen',
                'type' => 'Application',
                'deskripsi' => 'CPU usage di atas 90%',
                'keterangan' => 'Current: 92%',
                'status' => 'Aktif',
                'durasi' => '1 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '15 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Info',
                'target' => 'App-002',
                'target_sub' => 'App Kemendikdasmen',
                'type' => 'Application',
                'deskripsi' => 'Memory usage mencapai 95%',
                'keterangan' => 'Current: 94%',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '15 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Info',
                'target' => 'App-003',
                'target_sub' => 'App Kemendikdasmen',
                'type' => 'Application',
                'deskripsi' => 'Memory usage mencapai 95%',
                'keterangan' => 'Current: 94%',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '10 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Warning',
                'target' => 'App-004',
                'target_sub' => 'App Kemendikdasmen',
                'type' => 'Application',
                'deskripsi' => 'Storage Penuh',
                'keterangan' => 'Space tersisa tinggal 200 GB',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '15 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Info',
                'target' => 'App-005',
                'target_sub' => 'App Kemendikdasmen',
                'type' => 'Application',
                'deskripsi' => 'Memory usage mencapai 95%',
                'keterangan' => 'Current: 94%',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '18 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Info',
                'target' => 'App-006',
                'target_sub' => 'App Kemendikdasmen',
                'type' => 'Application',
                'deskripsi' => 'Disk usage mencapai 98%',
                'keterangan' => 'Current: 90%',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
        ];

        // ===== DATA ALERT - DOMAIN =====
        $domainAlerts = [
            [
                'waktu' => '20 Agu 2026',
                'jam' => '10:00 - 11:00',
                'level' => 'Critical',
                'target' => 'WebGTK-001',
                'target_sub' => 'Domain Web',
                'type' => 'Domain',
                'deskripsi' => 'CPU usage di atas 90%',
                'keterangan' => 'Current: 92%',
                'status' => 'Aktif',
                'durasi' => '1 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '15 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Info',
                'target' => 'WebGTK-002',
                'target_sub' => 'Domain Web',
                'type' => 'Domain',
                'deskripsi' => 'Memory usage mencapai 95%',
                'keterangan' => 'Current: 94%',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '15 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Info',
                'target' => 'WebGTK-003',
                'target_sub' => 'Domain Web',
                'type' => 'Domain',
                'deskripsi' => 'Memory usage mencapai 95%',
                'keterangan' => 'Current: 94%',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '10 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Warning',
                'target' => 'WebGTK-004',
                'target_sub' => 'Domain Web',
                'type' => 'Domain',
                'deskripsi' => 'Storage Penuh',
                'keterangan' => 'Space tersisa tinggal 200 GB',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '15 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Info',
                'target' => 'WebGTK-005',
                'target_sub' => 'Domain Web',
                'type' => 'Domain',
                'deskripsi' => 'Memory usage mencapai 95%',
                'keterangan' => 'Current: 94%',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
            [
                'waktu' => '18 Nov 2026',
                'jam' => '13:00-15:00',
                'level' => 'Info',
                'target' => 'WebGTK-006',
                'target_sub' => 'Domain Web',
                'type' => 'Domain',
                'deskripsi' => 'Disk usage mencapai 98%',
                'keterangan' => 'Current: 90%',
                'status' => 'Aktif',
                'durasi' => '2 Jam',
                'pic' => 'Slowrance Stroll'
            ],
        ];

        return view('pages.alerts.index', compact(
            'stats',
            'serverAlerts',
            'applicationAlerts',
            'domainAlerts'
        ));
    }
}