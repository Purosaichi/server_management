@extends('layouts.app')

@section('title', 'Maintenance - GTK Monitoring')
@section('page-title', 'Maintenance')
@section('page-subtitle', 'Daftar jadwal pemeliharaan sistem')

@section('content')
{{-- CSS Terpisah --}}
<link rel="stylesheet" href="{{ asset('css/maintenance.css') }}">

{{-- Kartu Statistik --}}
<div class="mt-stats-grid">
    <div class="mt-stat-card">
        <p class="mt-stat-label">Total Maintenance</p>
        <p class="mt-stat-value">{{ $stats['total'] }}</p>
        <p class="mt-stat-sub">Periode ini ({{ $stats['period'] }})</p>
    </div>
    <div class="mt-stat-card">
        <p class="mt-stat-label">Maintenance Selesai</p>
        <p class="mt-stat-value green">{{ $stats['completed'] }}</p>
        <p class="mt-stat-sub">{{ round(($stats['completed'] / $stats['total']) * 100) }}% dari total</p>
    </div>
    <div class="mt-stat-card">
        <p class="mt-stat-label">In Progress</p>
        <p class="mt-stat-value blue">{{ $stats['in_progress'] }}</p>
        <p class="mt-stat-sub">Sedang berlangsung</p>
    </div>
    <div class="mt-stat-card">
        <p class="mt-stat-label">Scheduled</p>
        <p class="mt-stat-value yellow">{{ $stats['scheduled'] }}</p>
        <p class="mt-stat-sub">Akan datang</p>
    </div>
</div>

{{-- Tabel Maintenance --}}
<div class="mt-table-container">
    <div class="mt-table-header">
        <h3 class="mt-table-title">Daftar Maintenance</h3>
        <div class="mt-filter-group">
            <button class="mt-filter-btn active">Semua</button>
            <button class="mt-filter-btn inactive">Server</button>
            <button class="mt-filter-btn inactive">Application</button>
            <button class="mt-filter-btn inactive">Domain</button>
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
            <tbody>
                @foreach($maintenances as $mt)
                <tr>
                    <td><span class="mt-id">{{ $mt['id'] }}</span></td>
                    <td>{{ $mt['target'] }}</td>
                    <td>{{ $mt['jenis'] }}</td>
                    <td>
                        <span class="mt-badge 
                            {{ $mt['kategori'] == 'Application Server' ? 'mt-badge-purple' : '' }}
                            {{ $mt['kategori'] == 'Application' ? 'mt-badge-blue' : '' }}
                            {{ $mt['kategori'] == 'Database Server' ? 'mt-badge-orange' : '' }}
                            {{ $mt['kategori'] == 'Domain' ? 'mt-badge-green' : '' }}
                        ">
                            {{ $mt['kategori'] }}
                        </span>
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
        Menampilkan {{ count($maintenances) }} dari {{ $stats['total'] }} data
    </div>
</div>
@endsection