@extends('layouts.app')

@section('title', 'Domain')

@section('page-title', 'Domain')
@section('page-subtitle', 'Daftar semua domain yang terdaftar')

@section('content')
<link rel="stylesheet" href="{{ asset('css/domain.css') }}">

<div class="header">
    <h2 class="title-header">Domains</h2>
</div>

<div class="dmn-table-container">
    <div class="dmn-table-header">
        <h3 class="dmn-table-title">Daftar Domain</h3>
        <span class="dmn-table-count">Total: {{ count($domains) }} domain</span>
    </div>
    <div class="dmn-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Domain</th>
                    <th>Application</th>
                    <th>Status</th>
                    <th>PIC</th>
                    <th>SSL Certificate</th>
                    <th>Last Activity</th>
                    <th class="dmn-text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($domains as $domain)
                <tr>
                    <td><span class="dmn-name">{{ $domain['domain'] }}</span></td>
                    <td>{{ $domain['application'] }}</td>
                    <td>
                        <span class="dmn-badge 
                            {{ $domain['status'] == 'Aktif' ? 'dmn-badge-green' : 'dmn-badge-red' }}">
                            {{ $domain['status'] }}
                        </span>
                    </td>
                    <td>{{ $domain['pic'] }}</td>
                    <td>
                        <span class="dmn-badge 
                            {{ $domain['ssl'] == 'Aktif' ? 'dmn-badge-green' : 'dmn-badge-red' }}">
                            {{ $domain['ssl'] }}
                        </span>
                    </td>
                    <td>{{ $domain['last_activity'] }}</td>
                    <td class="dmn-text-center">
                        <a href="#" class="dmn-btn-detail">
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