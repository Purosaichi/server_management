@extends('layouts.app')

@section('title', 'Server')

@section('page-title', 'Server')
@section('page-subtitle', 'Daftar semua server yang dimonitoring')

@section('content')
{{-- CSS Terpisah --}}
<link rel="stylesheet" href="{{ asset('css/server.css') }}">

<div class="srv-table-container">
    <div class="srv-table-header">
        <h3 class="srv-table-title">Daftar Server</h3>
        <span class="srv-table-count">Total: {{ count($servers) }} server</span>
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
                @foreach($servers as $server)
                <tr>
                    <td><span class="srv-name">{{ $server['name'] }}</span></td>
                    <td>{{ $server['ip'] }}</td>
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
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection