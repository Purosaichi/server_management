@extends('layouts.app')

@section('title', 'Server')

@section('page-title', 'Server')
@section('page-subtitle', 'Daftar semua server yang dimonitoring')

@section('content')
<link rel="stylesheet" href="{{ asset('css/server.css') }}">

<div class="header">
    <h2 class="title-header">Server</h2>
</div>

{{-- Notifikasi --}}
@if(session('success'))
<div class="srv-alert srv-alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="srv-alert srv-alert-error">
    {{ session('error') }}
</div>
@endif

<div class="srv-table-container">
    <div class="srv-table-header">
        <h3 class="srv-table-title">Daftar Server</h3>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span class="srv-table-count">Total Server: {{ count($servers) }} server</span>
            <a href="{{ route('server.create') }}" class="srv-btn-add">+ Tambah Server</a>
        </div>
    </div>
    <div class="srv-table-wrapper">
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
                    <th class="srv-text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servers as $server)
                <tr>
                    <td><span class="srv-name">{{ $server['name'] }}</span></td>
                    <td>{{ $server['ip_address'] }}</td>
                    <td>
                        <span class="srv-badge 
                            {{ $server['status'] == 'Online' ? 'srv-badge-online' : 'srv-badge-offline' }}">
                            {{ $server['status'] }}
                        </span>
                    </td>
                    <td>{{ $server['cpu'] }}</td>
                    <td>{{ $server['ram'] }}</td>
                    <td>{{ $server['disk'] }}</td>
                    <td>{{ $server['uptime'] }}</td>
                    <td class="srv-text-center">
                        <a href="{{ route('server.detail', $server['id']) }}" class="srv-btn-detail">
                            Selengkapnya →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #9ca3af; padding: 2rem;">
                        Belum ada data server.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection