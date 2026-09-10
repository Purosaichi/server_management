@extends('layouts.app')

@section('title', 'Licenses')

@section('page-title', 'Licenses')
@section('page-subtitle', 'Daftar lisensi aplikasi dan server')

@section('content')
<link rel="stylesheet" href="{{ asset('css/licenses.css') }}">

<div class="header">
    <h2 class="title-header">Licenses</h2>
</div>

<div class="lic-table-container">
    <div class="lic-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nama Lisensi</th>
                    <th>Provider</th>
                    <th>Status</th>
                    <th>Jumlah Lisensi</th>
                    <th>Tanggal Expired</th>
                </tr>
            </thead>
            <tbody>
                @foreach($licenses as $lic)
                <tr>
                    <td>
                        <div class="lic-name-cell">
                            <img src="{{ asset('images/' . $lic['logo']) }}" alt="{{ $lic['name'] }}" class="lic-logo">
                            <span class="lic-name-text">{{ $lic['name'] }}</span>
                        </div>
                    </td>
                    <td>{{ $lic['provider'] }}</td>
                    <td>
                        <span class="lic-badge 
                            {{ $lic['status_type'] == 'safe' ? 'lic-badge-safe' : 
                               ($lic['status_type'] == 'warning' ? 'lic-badge-warning' : 'lic-badge-expired') }}">
                            {{ $lic['status'] }}
                        </span>
                    </td>
                    <td>{{ $lic['jumlah'] }}</td>
                    <td>
                        <div class="lic-expired 
                            {{ $lic['expired_type'] == 'safe' ? 'lic-expired-safe' : 
                               ($lic['expired_type'] == 'warning' ? 'lic-expired-warning' : 'lic-expired-expired') }}">
                            {{ $lic['expired'] }}
                            @if($lic['expired_note'])
                                <span class="lic-expired-note">{{ $lic['expired_note'] }}</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<footer class="footer">
    © 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen
</footer>
@endsection