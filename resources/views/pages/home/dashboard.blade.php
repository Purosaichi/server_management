@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="header">
    <h2 class="title-header">Dashboard</h2>
</div>

<!--statistik-->
<div class="grid-stats">
    <div class="stat-card">
        <p class="stat-label">Total Server</p>
        <p class="stat-value">{{ $stats['total_server'] }}</p>
    </div>
    
    <div class="stat-card">
        <p class="stat-label">Total Aplikasi</p>
        <p class="stat-value">{{ $stats['total_aplikasi'] }}</p>
    </div>
    
    <div class="stat-card">
        <p class="stat-label">Monitoring</p>
        <p class="stat-value blue">{{ $stats['monitoring'] }}</p>
    </div>
    
    <div class="stat-card">
        <p class="stat-label">Maintenance</p>
        <p class="stat-value yellow">{{ $stats['maintenance'] }}</p>
    </div>
    
    <div class="stat-card">
        <p class="stat-label">Alerts</p>
        <p class="stat-value red">{{ $stats['alerts'] }}</p>
    </div>
</div>

<!-- Tabel Server -->
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
        <a href="/server" class="link-all">Lihat Semua →</a>
    </div>
</div>

<!-- Tabel Aplikasi --> 
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
        <a href="/application" class="link-all">Lihat Semua →</a>
    </div>
</div>
@endsection