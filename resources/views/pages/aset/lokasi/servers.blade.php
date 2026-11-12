@extends('layouts.app')

@section('title', 'Server di Lokasi Aset')
@section('page-title', 'Server di Lokasi Aset')
@section('page-subtitle', 'Daftar server yang berada di lokasi fisik yang sama')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-location.css') }}">

<div class="asset-location-back-wrapper">
    <a href="{{ route('aset.lokasi.detail', $location->id_lokasi_aset) }}" class="asset-location-back-button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Detail Lokasi
    </a>
</div>

<div class="asset-location-page">
    <div class="asset-location-header">
        <div>
            <span class="asset-location-eyebrow">Server pada lokasi fisik yang sama</span>
            <h1 class="asset-location-title">{{ $location->nama_rak ?: $location->nama_ruangan ?: 'Lokasi Aset' }}</h1>
            <p class="asset-location-subtitle">
                {{ collect([
                    $location->nama_data_center,
                    $location->nama_gedung,
                    $location->lantai,
                    $location->nama_ruangan,
                    $location->zona,
                    $location->kode_rak,
                ])->filter()->implode(' - ') ?: 'Rincian lokasi belum tersedia' }}
            </p>
        </div>
        <div class="asset-location-count" aria-label="Jumlah server di lokasi ini">
            <i class="fas fa-server" aria-hidden="true"></i>
            <strong>{{ $servers->count() }}</strong>
            <span>server</span>
        </div>
    </div>

    <div class="asset-location-table-wrap">
        <table class="asset-location-table">
            <thead>
                <tr>
                    <th>Nama Server</th>
                    <th>Kode Aset</th>
                    <th>Hostname</th>
                    <th>IP Produksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servers as $server)
                    <tr>
                        <td>{{ $server->nama_server }}</td>
                        <td>{{ $server->kode_aset ?: '-' }}</td>
                        <td>{{ $server->hostname ?: '-' }}</td>
                        <td>{{ $server->alamat_ip_produksi ?: '-' }}</td>
                        <td>
                            <a href="{{ route('server.detail', $server->id_server) }}" class="asset-location-edit-button">
                                <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                Detail Server
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="asset-location-empty">Belum ada server di lokasi fisik ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
