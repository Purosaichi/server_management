@extends('layouts.app')

@section('title', 'Detail Switch')

@section('page-title', 'Detail Switch')
@section('page-subtitle', 'Informasi lengkap switch')

@section('content')
<link rel="stylesheet" href="{{ asset('css/network-device.css') }}">

<div class="nd-back-wrapper">
    <a href="{{ route('network-device.switch') }}" class="nd-back-btn">← Back</a>
</div>

<div class="nd-detail-card">
    <h2 class="nd-detail-title">Switch #{{ $switch->id_switch }}</h2>
    
    <div class="nd-detail-grid">
        <div class="nd-detail-item">
            <span class="nd-detail-label">Jenis Switch</span>
            <span class="nd-detail-value">{{ $switch->jenis_switch ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">Lapisan Jaringan</span>
            <span class="nd-detail-value">{{ $switch->lapisan_jaringan ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">Total Port</span>
            <span class="nd-detail-value">{{ $switch->jumlah_port ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">Port Terpakai</span>
            <span class="nd-detail-value">{{ $switch->jumlah_port_terpakai ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">Port Tersedia</span>
            <span class="nd-detail-value">{{ $switch->jumlah_port_tersedia ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">Kecepatan Port</span>
            <span class="nd-detail-value">{{ $switch->kecepatan_port ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">Jumlah Port Uplink</span>
            <span class="nd-detail-value">{{ $switch->jumlah_port_uplink ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">Status Stack</span>
            <span class="nd-detail-value">{{ $switch->status_stack ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">Anggota Stack</span>
            <span class="nd-detail-value">{{ $switch->jumlah_anggota_stack ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">Jumlah VLAN</span>
            <span class="nd-detail-value">{{ $switch->jumlah_vlan ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">IP Manajemen</span>
            <span class="nd-detail-value">{{ $switch->alamat_ip_manajemen ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">MAC Address</span>
            <span class="nd-detail-value">{{ $switch->alamat_mac ?? '-' }}</span>
        </div>
        <div class="nd-detail-item">
            <span class="nd-detail-label">ID Aset</span>
            <span class="nd-detail-value">{{ $switch->id_Aset ?? '-' }}</span>
        </div>
    </div>
</div>
@endsection