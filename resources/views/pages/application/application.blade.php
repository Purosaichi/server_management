@extends('layouts.app')

@section('title', 'Application')

@section('page-title', 'Application')
@section('page-subtitle', 'Daftar semua aplikasi yang dimonitoring')

@section('content')
{{-- CSS Terpisah --}}
<link rel="stylesheet" href="{{ asset('css/application.css') }}">

<div class="app-table-container">
    <div class="app-table-header">
        <h3 class="app-table-title">Daftar Aplikasi</h3>
        <span class="app-table-count">Total: {{ count($applications) }} aplikasi</span>
    </div>
    <div class="app-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Application</th>
                    <th>Server</th>
                    <th>Status</th>
                    <th>Domain</th>
                    <th>Licenses</th>
                    <th>Next Maintenance</th>
                    <th class="app-text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $app)
                <tr>
                    <td><span class="app-name">{{ $app['name'] }}</span></td>
                    <td>{{ $app['server'] }}</td>
                    <td>
                        <span class="app-badge 
                            {{ $app['status'] == 'Aktif' ? 'app-badge-green' : 'app-badge-red' }}">
                            {{ $app['status'] }}
                        </span>
                    </td>
                    <td>{{ $app['domain'] }}</td>
                    <td>
                        <span class="app-badge 
                            {{ $app['licenses'] == 'Aktif' ? 'app-badge-green' : 
                               ($app['licenses'] == 'Akan Expired' ? 'app-badge-yellow' : 'app-badge-red') }}">
                            {{ $app['licenses'] }}
                        </span>
                    </td>
                    <td>{{ $app['maintenance'] }}</td>
                    <td class="app-text-center">
                        <a href="{{ route('application.detail', $app['id']) }}" class="app-btn-detail">
                            Selengkapnya →
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection