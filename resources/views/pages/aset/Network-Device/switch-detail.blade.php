@extends('layouts.app')

@section('title', 'Detail Switch')

@section('page-title', 'Detail Switch')
@section('page-subtitle', 'Informasi lengkap switch')

@section('content')
<link rel="stylesheet" href="{{ asset('css/network-device.css') }}">

<div class="nd-switch-detail">
    <div class="nd-back-wrapper">
        <a href="{{ route('network-device.switch') }}" class="nd-back-btn">
            <span aria-hidden="true">←</span>
            Back
        </a>
    </div>

    <section class="nd-switch-hero" aria-labelledby="switchDetailTitle">
        <div class="nd-switch-hero-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <rect x="3.5" y="7" width="17" height="10" rx="2" stroke="currentColor" stroke-width="1.7"/>
                <path d="M7 10.5h.01M10 10.5h.01M13 10.5h.01M16 10.5h.01M8 14h8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="nd-switch-hero-content">
            <span class="nd-switch-eyebrow">Detail perangkat jaringan</span>
            <h2 id="switchDetailTitle">Switch #{{ $switch->id_switch }}</h2>
            <p>Ringkasan kapasitas port, fitur jaringan, dan identitas perangkat.</p>
        </div>
        <div class="nd-switch-hero-tags">
            <span class="nd-switch-tag">{{ $switch->jenis_switch ?? 'Jenis tidak diketahui' }}</span>
            <span class="nd-switch-tag nd-switch-tag-muted">{{ $switch->lapisan_jaringan ?? 'Layer tidak diketahui' }}</span>
        </div>
    </section>

    <div class="nd-switch-sections">
        <section class="nd-switch-card" aria-labelledby="switchPortsTitle">
            <div class="nd-switch-section-heading">
                <span class="nd-switch-section-icon" aria-hidden="true">01</span>
                <div>
                    <h3 id="switchPortsTitle">Kapasitas Port</h3>
                    <p>Jumlah dan kecepatan koneksi pada switch.</p>
                </div>
            </div>
            <div class="nd-switch-metrics">
                <div class="nd-switch-metric">
                    <span class="nd-switch-metric-label">Total port</span>
                    <strong>{{ $switch->jumlah_port ?? '-' }}</strong>
                </div>
                <div class="nd-switch-metric">
                    <span class="nd-switch-metric-label">Port terpakai</span>
                    <strong>{{ $switch->jumlah_port_terpakai ?? '-' }}</strong>
                </div>
                <div class="nd-switch-metric">
                    <span class="nd-switch-metric-label">Port tersedia</span>
                    <strong>{{ $switch->jumlah_port_tersedia ?? '-' }}</strong>
                </div>
            </div>
            <div class="nd-switch-details-grid">
                <div class="nd-switch-detail-item">
                    <span class="nd-switch-detail-label">Kecepatan port</span>
                    <span class="nd-switch-detail-value">{{ $switch->kecepatan_port ?? '-' }}</span>
                </div>
                <div class="nd-switch-detail-item">
                    <span class="nd-switch-detail-label">Jumlah port uplink</span>
                    <span class="nd-switch-detail-value">{{ $switch->jumlah_port_uplink ?? '-' }}</span>
                </div>
            </div>
        </section>

        <section class="nd-switch-card" aria-labelledby="switchNetworkTitle">
            <div class="nd-switch-section-heading">
                <span class="nd-switch-section-icon" aria-hidden="true">02</span>
                <div>
                    <h3 id="switchNetworkTitle">Fitur Jaringan</h3>
                    <p>Status stack dan segmentasi jaringan.</p>
                </div>
            </div>
            <div class="nd-switch-details-grid">
                <div class="nd-switch-detail-item">
                    <span class="nd-switch-detail-label">Status stack</span>
                    <span class="nd-switch-detail-value">{{ $switch->status_stack ?? '-' }}</span>
                </div>
                <div class="nd-switch-detail-item">
                    <span class="nd-switch-detail-label">Anggota stack</span>
                    <span class="nd-switch-detail-value">{{ $switch->jumlah_anggota_stack ?? '-' }}</span>
                </div>
                <div class="nd-switch-detail-item">
                    <span class="nd-switch-detail-label">Jumlah VLAN</span>
                    <span class="nd-switch-detail-value">{{ $switch->jumlah_vlan ?? '-' }}</span>
                </div>
            </div>
        </section>

        <section class="nd-switch-card" aria-labelledby="switchIdentityTitle">
            <div class="nd-switch-section-heading">
                <span class="nd-switch-section-icon" aria-hidden="true">03</span>
                <div>
                    <h3 id="switchIdentityTitle">Identitas Perangkat</h3>
                    <p>Informasi alamat dan pencatatan aset.</p>
                </div>
            </div>
            <div class="nd-switch-details-grid">
                <div class="nd-switch-detail-item">
                    <span class="nd-switch-detail-label">IP manajemen</span>
                    <span class="nd-switch-detail-value nd-switch-monospace">{{ $switch->alamat_ip_manajemen ?? '-' }}</span>
                </div>
                <div class="nd-switch-detail-item">
                    <span class="nd-switch-detail-label">MAC address</span>
                    <span class="nd-switch-detail-value nd-switch-monospace">{{ $switch->alamat_mac ?? '-' }}</span>
                </div>
                <div class="nd-switch-detail-item">
                    <span class="nd-switch-detail-label">ID aset</span>
                    <span class="nd-switch-detail-value">{{ $switch->id_Aset ?? '-' }}</span>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection