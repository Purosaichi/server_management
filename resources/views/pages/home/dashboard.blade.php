@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="header">
    <h2 class="title-header">Dashboard</h2>
</div>

<!--statistik-->
<div class="grid-stats-4">
    <!--Total Server-->
    <div class="stat-card">
        <p class="stat-label">Total Server</p>
        <p class="stat-value">{{ $stats['total_server'] }}</p>
        <p class="stat-detail">
            <span class="green">{{ $stats['server_online'] }} Online</span> · 
            <span class="red">{{ $stats['server_offline'] }} Offline</span>
        </p>
    </div>

    <!--Total Aplikasi-->
    <div class="stat-card">
        <p class="stat-label">Total Aplikasi</p>
        <p class="stat-value">{{ $stats['total_aplikasi'] }}</p>
        <p class="stat-detail">
            <span class="green">{{ $stats['app_aktif'] }} Aktif</span> · 
            <span class="red">{{ $stats['app_down'] }} Down</span>
        </p>
    </div>

    <!--Jadwal Maintenance-->
    <div class="stat-card">
        <p class="stat-label">Jadwal Maintenance</p>
        <p class="stat-value">{{ $stats['jadwal_maintenance'] }}</p>
        <p class="stat-detail">Dalam 5 hari ke depan</p>
    </div>

    <!--Akan Expired-->
    <div class="stat-card">
        <p class="stat-label">Akan Expired</p>
        <p class="stat-value">{{ $stats['akan_expired'] }}</p>
        <p class="stat-detail">Domain & license</p>
    </div>
</div>

<!--table server-->
<div class="table-container mb-8">
    <div class="table-header">
        <h3 class="table-title">Server</h3>
        <span class="table-count">Menampilkan 6 dari {{ $stats['total_server'] }}</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Server</th>
                    <th>IP Address</th>
                    <th>Status</th>
                    <th>CPU</th>
                    <th>RAM</th>
                    <th>DISC</th>
                    <th>Up Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servers as $server)
                <tr>
                    <td><span class="server-name">{{ $server['name'] }}</span></td>
                    <td>{{ $server['ip'] }}</td>
                    <td>
                        <span class="badge {{ $server['status'] == 'Online' ? 'badge-green' : 'badge-red' }}">
                            {{ $server['status'] }}
                        </span>
                    </td>
                    <td>{{ $server['cpu'] }}</td>
                    <td>{{ $server['ram'] }}</td>
                    <td>{{ $server['disk'] }}</td>
                    <td>{{ $server['uptime'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        <a href="{{ route('server.index') }}" class="link-all">Lihat Semua →</a>
    </div>
</div>

<!--table aplikasi-->
<div class="table-container">
    <div class="table-header">
        <h3 class="table-title">Application</h3>
        <span class="table-count">Menampilkan 5 dari {{ $stats['total_aplikasi'] }}</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Aplikasi</th>
                    <th>Server</th>
                    <th>Status</th>
                    <th>Domain</th>
                    <th>Licenses</th>
                    <th>Next Maintenance</th>
                </tr>
            </thead>    
            <tbody>
                @foreach($applications as $app)
                <tr>
                    <td><span class="app-name">{{ $app['name'] }}</span></td>
                    <td>{{ $app['server'] }}</td>
                    <td>
                        <span class="badge {{ $app['status'] == 'Aktif' ? 'badge-green' : 'badge-red' }}">
                            {{ $app['status'] }}
                        </span>
                    </td>
                    <td>{{ $app['domain'] }}</td>
                    <td>{{ $app['licenses'] }}</td>
                    <td>{{ $app['maintenance'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        <a href="{{ route('application.index') }}" class="link-all">Lihat Semua →</a>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection