@extends('layouts.app')

@section('title', 'Detail Router')

@section('page-title', 'Detail Router')
@section('page-subtitle', 'Informasi lengkap router')

@section('content')
<link rel="stylesheet" href="{{ asset('css/network-device.css') }}">

<div class="nd-back-wrapper">
    <a href="{{ route('network-device.router') }}" class="nd-back-btn">← Back</a>
</div>

<div class="nd-detail-card">
    <h2 class="nd-detail-title">Router #{{ $router->id_router }}</h2>
    
    <div class="nd-detail-grid">
        <div class="nd-detail-item">
            <span class="nd-detail-label">Jenis Router</span>
            <span class="nd-detail-value">{{ $router->jenis_router ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">Kapasitas Bandwidth</span>
            <span class="nd-detail-value">{{ $router->kapasitas_bandwidth ? $router->kapasitas_bandwidth . ' Mbps' : '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">Protokol Routing</span>
            <span class="nd-detail-value">{{ $router->protokol_routing ?? '-' }}</span>
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
            <span class="nd-detail-label">Alamat IP Manajemen</span>
            <span class="nd-detail-value">{{ $router->alamat_ip_manajemen ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">ID Aset</span>
            <span class="nd-detail-value">{{ $router->id_Aset ?? '-' }}</span>
        </div>
    </div>
</div>
@endsection