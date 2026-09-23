{{-- ============================================================ --}}
{{-- FEATURE: DASHBOARD --}}
{{-- ============================================================ --}}

@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Beranda')
@section('page-subtitle', 'Overview Monitoring Sistem')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="header">
    <h2 class="title-header">Dashboard</h2>
</div>

{{-- ===== KARTU STATISTIK ===== --}}
<div class="grid-stats-4">
    <div class="stat-card-new">
        <p class="stat-label">Total Server</p>
        <p class="stat-value">{{ $stats->total_server ?? 0 }}</p>
        <p class="stat-detail">
            <span class="green">{{ $stats->server_online ?? 0 }} Online</span> · 
            <span class="red">{{ $stats->server_offline ?? 0 }} Offline</span>
        </p>
    </div>

    <div class="stat-card-new">
        <p class="stat-label">Total Aplikasi</p>
        <p class="stat-value">{{ $stats->total_aplikasi ?? 0 }}</p>
        <p class="stat-detail">
            <span class="green">{{ $stats->app_aktif ?? 0 }} Aktif</span> · 
            <span class="red">{{ $stats->app_down ?? 0 }} Down</span>
        </p>
    </div>

    <div class="stat-card-new">
        <p class="stat-label">Jadwal Maintenance</p>
        <p class="stat-value">{{ $stats->jadwal_maintenance ?? 0 }}</p>
        <p class="stat-detail">Dalam 5 hari ke depan</p>
    </div>

    <div class="stat-card-new">
        <p class="stat-label">Akan Expired</p>
        <p class="stat-value">{{ $stats->akan_expired ?? 0 }}</p>
        <p class="stat-detail">Domain & license</p>
    </div>
</div>

{{-- ===== TABEL SERVER ===== --}}
<div class="table-container mb-8">
    <div class="table-header">
        <h3 class="table-title">Server</h3>
        <span class="table-count">Menampilkan {{ count($servers) }} dari {{ $stats->total_server ?? 0 }}</span>
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
                @forelse($servers as $server)
                <tr>
                    <td><span class="server-name">{{ $server['name'] }}</span></td>
                    <td>{{ $server['ip_address'] }}</td>
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
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #9ca3af;">Belum ada data server.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        <a href="{{ route('server.index') }}" class="link-all">Lihat Semua →</a>
    </div>
</div>

{{-- ===== TABEL APLIKASI ===== --}}
<div class="table-container mb-8">
    <div class="table-header">
        <h3 class="table-title">Application</h3>
        <span class="table-count">Menampilkan {{ count($applications) }} dari {{ $stats->total_aplikasi ?? 0 }}</span>
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
                @forelse($applications as $app)
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
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #9ca3af;">Belum ada data aplikasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        <a href="{{ route('application.index') }}" class="link-all">Lihat Semua →</a>
    </div>
</div>

{{-- ===== REMINDER KADALUARSA ===== --}}
@if($reminders->count() > 0)
<div class="table-container">
    <div class="table-header">
        <h3 class="table-title">Reminder Kadaluarsa</h3>
        <span class="table-count">Dalam 60 hari ke depan</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Jenis</th>
                    <th>Nama</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Sisa Hari</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reminders as $r)
                <tr>
                    <td>{{ $r->jenis }}</td>
                    <td><span class="app-name">{{ $r->nama }}</span></td>
                    <td>{{ $r->tanggal_kadaluarsa }}</td>
                    <td>
                        <span class="badge {{ $r->sisa_hari <= 7 ? 'badge-red' : ($r->sisa_hari <= 30 ? 'badge-yellow' : 'badge-green') }}">
                            {{ $r->sisa_hari }} hari
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection