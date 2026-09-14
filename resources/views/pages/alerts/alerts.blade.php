@extends('layouts.app')

@section('title', 'Alerts')

@section('page-title', 'Alerts')
@section('page-subtitle', 'Peringatan kondisi server dan aplikasi')

@section('content')
<link rel="stylesheet" href="{{ asset('css/alerts.css') }}">

<div class="alerts-header">
    <h1>Alerts</h1>
    <p>Peringatan kondisi server dan aplikasi.</p>
</div>

{{-- Kartu Statistik --}}
<div class="alerts-stats-grid">
    <div class="alerts-stat-card">
        <div class="alerts-stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div class="alerts-stat-content">
            <p class="alerts-stat-label">Total Alerts</p>
            <p class="alerts-stat-value">{{ $stats['total'] }}</p>
            <p class="alerts-stat-sub">Semua Alert</p>
        </div>
    </div>
    <div class="alerts-stat-card">
        <div class="alerts-stat-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="alerts-stat-content">
            <p class="alerts-stat-label">Critical</p>
            <p class="alerts-stat-value">{{ $stats['critical'] }}</p>
            <p class="alerts-stat-sub">Perlu penanganan segera</p>
        </div>
    </div>
    <div class="alerts-stat-card">
        <div class="alerts-stat-icon yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="alerts-stat-content">
            <p class="alerts-stat-label">Warning</p>
            <p class="alerts-stat-value">{{ $stats['warning'] }}</p>
            <p class="alerts-stat-sub">Perlu diperhatikan</p>
        </div>
    </div>
    <div class="alerts-stat-card">
        <div class="alerts-stat-icon purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="alerts-stat-content">
            <p class="alerts-stat-label">Info</p>
            <p class="alerts-stat-value">{{ $stats['info'] }}</p>
            <p class="alerts-stat-sub">Informasi</p>
        </div>
    </div>
</div>

{{-- Tabel Alerts --}}
<div class="alerts-table-container">
    <div class="alerts-table-header">
        <h3 class="alerts-table-title">Daftar Maintenance</h3>
        <div class="alerts-filter-group">
            <button class="alerts-filter-btn active" data-filter="all">Semua</button>
            <button class="alerts-filter-btn inactive" data-filter="server">Server</button>
            <button class="alerts-filter-btn inactive" data-filter="application">Application</button>
            <button class="alerts-filter-btn inactive" data-filter="domain">Domain</button>
        </div>
    </div>
    <div class="alerts-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Level</th>
                    <th>Target</th>
                    <th>Type</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Durasi</th>
                    <th>PIC</th>
                </tr>   
            </thead>
            <tbody id="alerts-table-body">
                {{-- Server Alerts --}}
                @foreach($serverAlerts as $alert)
                <tr data-kategori="server">
                    <td>
                        <div>{{ $alert['waktu'] }}</div>
                        <div class="alerts-time">{{ $alert['jam'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-badge 
                            {{ $alert['level'] == 'Critical' ? 'alerts-badge-critical' : '' }}
                            {{ $alert['level'] == 'Warning' ? 'alerts-badge-warning' : '' }}
                            {{ $alert['level'] == 'Info' ? 'alerts-badge-info' : '' }}
                        ">
                            {{ $alert['level'] }}
                        </span>
                    </td>
                    <td>
                        <div>{{ $alert['target'] }}</div>
                        <div class="alerts-time">{{ $alert['target_sub'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-type-badge alerts-type-server">{{ $alert['type'] }}</span>
                    </td>
                    <td>
                        <div>{{ $alert['deskripsi'] }}</div>
                        <div class="alerts-desc-sub">{{ $alert['keterangan'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-status 
                            {{ $alert['status'] == 'Aktif' ? 'alerts-status-active' : '' }}
                        ">
                            {{ $alert['status'] }}
                        </span>
                    </td>
                    <td>{{ $alert['durasi'] }}</td>
                    <td>{{ $alert['pic'] }}</td>
                </tr>
                @endforeach

                {{-- Application Alerts --}}
                @foreach($applicationAlerts as $alert)
                <tr data-kategori="application">
                    <td>
                        <div>{{ $alert['waktu'] }}</div>
                        <div class="alerts-time">{{ $alert['jam'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-badge 
                            {{ $alert['level'] == 'Critical' ? 'alerts-badge-critical' : '' }}
                            {{ $alert['level'] == 'Warning' ? 'alerts-badge-warning' : '' }}
                            {{ $alert['level'] == 'Info' ? 'alerts-badge-info' : '' }}
                        ">
                            {{ $alert['level'] }}
                        </span>
                    </td>
                    <td>
                        <div>{{ $alert['target'] }}</div>
                        <div class="alerts-time">{{ $alert['target_sub'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-type-badge alerts-type-application">{{ $alert['type'] }}</span>
                    </td>
                    <td>
                        <div>{{ $alert['deskripsi'] }}</div>
                        <div class="alerts-desc-sub">{{ $alert['keterangan'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-status 
                            {{ $alert['status'] == 'Aktif' ? 'alerts-status-active' : '' }}
                        ">
                            {{ $alert['status'] }}
                        </span>
                    </td>
                    <td>{{ $alert['durasi'] }}</td>
                    <td>{{ $alert['pic'] }}</td>
                </tr>
                @endforeach

                {{-- Domain Alerts --}}
                @foreach($domainAlerts as $alert)
                <tr data-kategori="domain">
                    <td>
                        <div>{{ $alert['waktu'] }}</div>
                        <div class="alerts-time">{{ $alert['jam'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-badge 
                            {{ $alert['level'] == 'Critical' ? 'alerts-badge-critical' : '' }}
                            {{ $alert['level'] == 'Warning' ? 'alerts-badge-warning' : '' }}
                            {{ $alert['level'] == 'Info' ? 'alerts-badge-info' : '' }}
                        ">
                            {{ $alert['level'] }}
                        </span>
                    </td>
                    <td>
                        <div>{{ $alert['target'] }}</div>
                        <div class="alerts-time">{{ $alert['target_sub'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-type-badge alerts-type-domain">{{ $alert['type'] }}</span>
                    </td>
                    <td>
                        <div>{{ $alert['deskripsi'] }}</div>
                        <div class="alerts-desc-sub">{{ $alert['keterangan'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-status 
                            {{ $alert['status'] == 'Aktif' ? 'alerts-status-active' : '' }}
                        ">
                            {{ $alert['status'] }}
                        </span>
                    </td>
                    <td>{{ $alert['durasi'] }}</td>
                    <td>{{ $alert['pic'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="alerts-table-footer">
        Menampilkan <span id="row-count">{{ count($serverAlerts) + count($applicationAlerts) + count($domainAlerts) }}</span> dari {{ $stats['total'] }} data
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>

{{-- JavaScript untuk Filter --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.alerts-filter-btn');
        const tableRows = document.querySelectorAll('#alerts-table-body tr');
        const rowCount = document.getElementById('row-count');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Ganti class active
                filterButtons.forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.add('inactive');
                });
                this.classList.add('active');
                this.classList.remove('inactive');

                const filter = this.getAttribute('data-filter');
                let visibleCount = 0;

                // Filter baris tabel
                tableRows.forEach(row => {
                    if (filter === 'all' || row.getAttribute('data-kategori') === filter) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update jumlah baris
                rowCount.textContent = visibleCount;
            });
        });
    });
</script>
@endsection