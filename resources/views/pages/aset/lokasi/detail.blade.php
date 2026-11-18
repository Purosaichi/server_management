@extends('layouts.app')

@section('title', 'Detail Lokasi Aset')
@section('page-title', 'Detail Lokasi Aset')
@section('page-subtitle', 'Informasi lengkap lokasi penempatan aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-location.css') }}">

<div class="asset-location-back-wrapper">
    <a href="{{ route('aset.lokasi') }}" class="asset-location-back-button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>
</div>

<div class="asset-location-detail-header">
    <div class="asset-location-detail-identity">
        <div>
            <span class="asset-location-detail-label">Lokasi Aset</span>
            <h1>{{ $location->nama_rak ?: $location->nama_ruangan ?: 'Lokasi #' . $location->id_lokasi_aset }}</h1>
            <p>{{ $location->nama_data_center ?: 'Data center belum ditentukan' }}</p>
        </div>
    </div>
    <div class="asset-location-detail-summary"><span class="asset-location-detail-label">Gedung / Ruangan</span><strong>{{ $location->nama_gedung ?: '-' }} / {{ $location->nama_ruangan ?: '-' }}</strong></div>
    <div class="asset-location-detail-summary"><span class="asset-location-detail-label">Zona</span><strong class="asset-location-detail-badge">{{ $location->zona ?: 'Tidak ada zona' }}</strong></div>
    <div class="asset-location-detail-summary"><span class="asset-location-detail-label">Posisi Rak</span><strong>{{ $location->posisi_rak ?: '-' }}</strong></div>
</div>

<div class="asset-location-detail-actions">
    <a href="{{ route('aset.lokasi.servers', $location->id_lokasi_aset) }}" class="asset-location-secondary-button"><i class="fas fa-server" aria-hidden="true"></i> Lihat Server di Lokasi Ini</a>
    <a href="{{ route('aset.lokasi.edit', $location->id_lokasi_aset) }}" class="asset-location-add-button"><i class="fas fa-pen" aria-hidden="true"></i> Edit Lokasi</a>
</div>

<div class="asset-location-info-grid">
    <section class="asset-location-info-card">
        <div class="asset-location-info-card-header"><h2>Informasi Lokasi</h2><i class="fas fa-building" aria-hidden="true"></i></div>
        <ul>
            <li><span>Data Center</span><strong>{{ $location->nama_data_center ?: '-' }}</strong></li>
            <li><span>Gedung</span><strong>{{ $location->nama_gedung ?: '-' }}</strong></li>
            <li><span>Lantai</span><strong>{{ $location->lantai ?: '-' }}</strong></li>
            <li><span>Ruangan</span><strong>{{ $location->nama_ruangan ?: '-' }}</strong></li>
            <li><span>Zona</span><strong>{{ $location->zona ?: '-' }}</strong></li>
        </ul>
    </section>
    <section class="asset-location-info-card">
        <div class="asset-location-info-card-header"><h2>Informasi Rak</h2><i class="fas fa-server" aria-hidden="true"></i></div>
        <ul>
            <li><span>Kode Rak</span><strong>{{ $location->kode_rak ?: '-' }}</strong></li>
            <li><span>Nama Rak</span><strong>{{ $location->nama_rak ?: '-' }}</strong></li>
            <li><span>Tinggi Rak</span><strong>{{ $location->tinggi_rak ? $location->tinggi_rak . 'U' : '-' }}</strong></li>
            <li><span>Posisi Rak</span><strong>{{ $location->posisi_rak ?: '-' }}</strong></li>
        </ul>
    </section>
    <section class="asset-location-info-card">
        <div class="asset-location-info-card-header"><h2>Penempatan</h2><i class="fas fa-calendar-check" aria-hidden="true"></i></div>
        <ul>
            <li><span>Tanggal Penempatan</span><strong>{{ $location->tanggal_penempatan ? \Carbon\Carbon::parse($location->tanggal_penempatan)->format('d M Y') : '-' }}</strong></li>
            <li><span>Keterangan</span><strong>{{ $location->keterangan ?: '-' }}</strong></li>
        </ul>
    </section>
</div>
@endsection
