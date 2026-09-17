@extends('layouts.app')

@section('title', 'Maintenance - GTK Monitoring')
@section('page-title', 'Maintenance')
@section('page-subtitle', 'Daftar jadwal pemeliharaan sistem')

@section('content')
<link rel="stylesheet" href="{{ asset('css/maintenance.css') }}">

<div class="header">
    <h2 class="title-header">Maintenance</h2>
</div>

<!--statistik-->
<div class="mt-stats-grid">
    <div class="mt-stat-card">
        <div class="mt-stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div class="mt-stat-content">
            <p class="mt-stat-label">Total Maintenance</p>
            <p class="mt-stat-value">{{ $stats['total'] }}</p>
            <p class="mt-stat-sub">Periode ini ({{ $stats['period'] }})</p>
        </div>
    </div>
    <div class="mt-stat-card">
        <div class="mt-stat-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="mt-stat-content">
            <p class="mt-stat-label">Completed</p>
            <p class="mt-stat-value green">{{ $stats['completed'] }}</p>
            <p class="mt-stat-sub">{{ round(($stats['completed'] / $stats['total']) * 100) }}% dari total</p>
        </div>
    </div>
    <div class="mt-stat-card">
        <div class="mt-stat-icon yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="mt-stat-content">
            <p class="mt-stat-label">In Progress</p>
            <p class="mt-stat-value blue">{{ $stats['in_progress'] }}</p>
            <p class="mt-stat-sub">Sedang berlangsung</p>
        </div>
    </div>
    <div class="mt-stat-card">
        <div class="mt-stat-icon purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="mt-stat-content">
            <p class="mt-stat-label">Scheduled</p>
            <p class="mt-stat-value yellow">{{ $stats['scheduled'] }}</p>
            <p class="mt-stat-sub">Akan datang</p>
        </div>
    </div>
</div>

<!--table maintenance-->
<div class="mt-table-container">
    <div class="mt-table-header">
        <h3 class="mt-table-title">Daftar Maintenance</h3>
        <div class="mt-filter-group">
            <button class="mt-filter-btn active" data-filter="all">Semua</button>
            <button class="mt-filter-btn inactive" data-filter="server">Server</button>
            <button class="mt-filter-btn inactive" data-filter="application">Application</button>
            <button class="mt-filter-btn inactive" data-filter="domain">Domain</button>
        </div>
    </div>
    <div class="mt-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Target</th>
                    <th>Jenis Maintenance</th>
                    <th>Kategori</th>
                    <th>Jadwal</th>
                    <th>Durasi</th>
                    <th>PIC</th>
                    <th>Status</th>
                </tr>   
            </thead>
            <tbody id="maintenance-table-body">

                <!--server-->
                @foreach($serverMaintenances as $mt)
                <tr data-kategori="server">
                    <td><span class="mt-id">{{ $mt['id'] }}</span></td>
                    <td>
                        <div>{{ $mt['target'] }}</div>
                        <div class="mt-time">{{ $mt['target_sub'] }}</div>
                    </td>
                    <td>{{ $mt['jenis'] }}</td>
                    <td>
                        <span class="mt-badge mt-badge-blue">{{ $mt['kategori'] }}</span>
                    </td>
                    <td>
                        <div>{{ $mt['jadwal'] }}</div>
                        <div class="mt-time">{{ $mt['jam'] }}</div>
                    </td>
                    <td>{{ $mt['durasi'] }}</td>
                    <td>{{ $mt['pic'] }}</td>
                    <td>
                        <span class="mt-status 
                            {{ $mt['status'] == 'In Progress' ? 'mt-status-progress' : '' }}
                            {{ $mt['status'] == 'Completed' ? 'mt-status-completed' : '' }}
                            {{ $mt['status'] == 'Scheduled' ? 'mt-status-scheduled' : '' }}
                        ">
                            {{ $mt['status'] }}
                        </span>
                    </td>
                </tr>
                @endforeach

                <!--application-->
                @foreach($applicationMaintenances as $mt)
                <tr data-kategori="application">
                    <td><span class="mt-id">{{ $mt['id'] }}</span></td>
                    <td>
                        <div>{{ $mt['target'] }}</div>
                        <div class="mt-time">{{ $mt['target_sub'] }}</div>
                    </td>
                    <td>{{ $mt['jenis'] }}</td>
                    <td>
                        <span class="mt-badge mt-badge-blue">{{ $mt['kategori'] }}</span>
                    </td>
                    <td>
                        <div>{{ $mt['jadwal'] }}</div>
                        <div class="mt-time">{{ $mt['jam'] }}</div>
                    </td>
                    <td>{{ $mt['durasi'] }}</td>
                    <td>{{ $mt['pic'] }}</td>
                    <td>
                        <span class="mt-status 
                            {{ $mt['status'] == 'In Progress' ? 'mt-status-progress' : '' }}
                            {{ $mt['status'] == 'Completed' ? 'mt-status-completed' : '' }}
                            {{ $mt['status'] == 'Scheduled' ? 'mt-status-scheduled' : '' }}
                        ">
                            {{ $mt['status'] }}
                        </span>
                    </td>
                </tr>
                @endforeach

                <!--domain-->
                @foreach($domainMaintenances as $mt)
                <tr data-kategori="domain">
                    <td><span class="mt-id">{{ $mt['id'] }}</span></td>
                    <td>
                        <div>{{ $mt['target'] }}</div>
                        <div class="mt-time">{{ $mt['target_sub'] }}</div>
                    </td>
                    <td>{{ $mt['jenis'] }}</td>
                    <td>
                        <span class="mt-badge mt-badge-green">{{ $mt['kategori'] }}</span>
                    </td>
                    <td>
                        <div>{{ $mt['jadwal'] }}</div>
                        <div class="mt-time">{{ $mt['jam'] }}</div>
                    </td>
                    <td>{{ $mt['durasi'] }}</td>
                    <td>{{ $mt['pic'] }}</td>
                    <td>
                        <span class="mt-status 
                            {{ $mt['status'] == 'In Progress' ? 'mt-status-progress' : '' }}
                            {{ $mt['status'] == 'Completed' ? 'mt-status-completed' : '' }}
                            {{ $mt['status'] == 'Scheduled' ? 'mt-status-scheduled' : '' }}
                        ">
                            {{ $mt['status'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-table-footer">
        Menampilkan <span id="row-count">{{ count($serverMaintenances) + count($applicationMaintenances) + count($domainMaintenances) }}</span> dari {{ $stats['total'] }} data
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>

<!--js buat filter-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.mt-filter-btn');
        const tableRows = document.querySelectorAll('#maintenance-table-body tr');
        const rowCount = document.getElementById('row-count');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Set active
                filterButtons.forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.add('inactive');
                });
                this.classList.add('active');
                this.classList.remove('inactive');

                const filter = this.getAttribute('data-filter');
                let visibleCount = 0;

                // Filter tabel
                tableRows.forEach(row => {
                    if (filter === 'all' || row.getAttribute('data-kategori') === filter) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update jumlah
                rowCount.textContent = visibleCount;
            });
        });
    });
</script>
@endsection