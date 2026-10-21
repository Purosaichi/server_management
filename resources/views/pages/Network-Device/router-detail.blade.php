@extends('layouts.app')

@section('title', 'Detail Router')

@section('page-title', 'Detail Router')
@section('page-subtitle', 'Informasi lengkap router')

@section('content')
<link rel="stylesheet" href="{{ asset('css/network-device.css') }}">

<div class="nd-router-detail">
    <div class="nd-back-wrapper">
        <a href="{{ route('network-device.router') }}" class="nd-back-btn">
            <span aria-hidden="true">←</span>
            Kembali ke daftar router
        </a>
    </div>

    <section class="nd-router-hero" aria-labelledby="routerDetailTitle">
        <div class="nd-router-hero-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 8.5h16v7H4zM7 5.5v3m10-3v3M7 15.5v3m10-3v3M8 12h.01M12 12h.01M16 12h.01" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="nd-router-hero-content">
            <span class="nd-router-eyebrow">Detail perangkat jaringan</span>
            <h2 id="routerDetailTitle">Router #{{ $router->id_router }}</h2>
            <p>Informasi konfigurasi dan identitas perangkat router.</p>
        </div>
        <span class="nd-router-type">{{ $router->jenis_router ?? 'Jenis tidak diketahui' }}</span>
    </section>

    <section class="nd-router-info-card" aria-labelledby="routerInfoTitle">
        <div class="nd-router-section-heading">
            <div>
                <h3 id="routerInfoTitle">Informasi Router</h3>
                <p>Rincian koneksi, protokol, dan fitur jaringan.</p>
            </div>
        </div>

        <div class="nd-detail-grid">
            <div class="nd-detail-item">
                <span class="nd-detail-label">Kapasitas Bandwidth</span>
                <span class="nd-detail-value">{{ $router->kapasitas_bandwidth ? $router->kapasitas_bandwidth . ' Mbps' : '-' }}</span>
            </div>
            <div class="nd-detail-item">
                <span class="nd-detail-label">Protokol Routing</span>
                <span class="nd-detail-value">{{ $router->protokol_routing ?? '-' }}</span>
            </div>
            <div class="nd-detail-item">
                <span class="nd-detail-label">Alamat IP Manajemen</span>
                <span class="nd-detail-value nd-router-monospace">{{ $router->alamat_ip_manajemen ?? '-' }}</span>
            </div>
            <div class="nd-detail-item">
                <span class="nd-detail-label">Jumlah Tunnel VPN</span>
                <span class="nd-detail-value">{{ $router->jumlah_tunnel_vpn ?? '-' }}</span>
            </div>
            <div class="nd-detail-item">
                <span class="nd-detail-label">Status VPN</span>
                <span class="nd-detail-value">{{ $router->status_vpn ?? '-' }}</span>
            </div>
            <div class="nd-detail-item">
                <span class="nd-detail-label">Status NAT</span>
                <span class="nd-detail-value">{{ $router->status_nat ?? '-' }}</span>
            </div>
            <div class="nd-detail-item">
                <span class="nd-detail-label">Status HA</span>
                <span class="nd-detail-value">{{ $router->status_ha ?? '-' }}</span>
            </div>
            <div class="nd-detail-item">
                <span class="nd-detail-label">ID Aset</span>
                <span class="nd-detail-value">{{ $router->id_Aset ?? '-' }}</span>
            </div>
        </div>
    </section>
</div>
@endsection