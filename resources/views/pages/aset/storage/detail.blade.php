@extends('layouts.app')

@section('title', 'Detail Storage')
@section('page-title', 'Detail Storage')
@section('page-subtitle', 'Informasi lengkap perangkat storage')

@section('content')
<link rel="stylesheet" href="{{ asset('css/storage.css') }}">

<div class="storage-back-wrapper">
    <a href="{{ route('aset.storage') }}" class="storage-back-button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>
</div>

<div class="storage-page">
    <div class="storage-header">
        <div class="storage-heading">
            <span class="storage-mark" aria-hidden="true"><i class="fas fa-hard-drive"></i></span>
            <div>
                <span class="storage-eyebrow">Inventaris Infrastruktur</span>
                <h1 class="storage-title">{{ $storage->nama_aset ?: 'Detail Storage' }}</h1>
                <p class="storage-subtitle">
                    {{ $storage->kode_aset ?: 'Aset ID ' . $storage->id_aset }}
                    · Storage ID {{ $storage->id_storage }}
                </p>
            </div>
        </div>
    </div>

    <dl class="storage-detail-grid">
        <div class="storage-detail-item">
            <dt>Jenis Storage</dt>
            <dd>{{ $storage->jenis_storage ?: '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Kapasitas Raw</dt>
            <dd>{{ $storage->kapasitas_raw !== null ? number_format((float) $storage->kapasitas_raw, 0, ',', '.') : '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Kapasitas Terpakai</dt>
            <dd>{{ $storage->kapasitas_terpakai !== null ? number_format((float) $storage->kapasitas_terpakai, 0, ',', '.') : '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Kapasitas Tersedia</dt>
            <dd>{{ $storage->kapasitas_tersedia !== null ? number_format((float) $storage->kapasitas_tersedia, 0, ',', '.') : '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Persentase Penggunaan</dt>
            <dd>{{ $storage->persentase_penggunaan !== null ? number_format((float) $storage->persentase_penggunaan, 2, ',', '.') . '%' : '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Jenis Disk</dt>
            <dd>{{ $storage->jenis_disk ?: '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Jumlah Disk</dt>
            <dd>{{ $storage->jumlah_disk ?? '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Tingkat RAID</dt>
            <dd>{{ $storage->tingkat_raid ?: '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Jumlah Pengendali</dt>
            <dd>{{ $storage->jumlah_pengendali ?? '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Protokol Storage</dt>
            <dd>{{ $storage->protokol_storage ?: '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Jumlah Volume</dt>
            <dd>{{ $storage->jumlah_volume ?? '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Jumlah LUN</dt>
            <dd>{{ $storage->jumlah_lun ?? '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Kapasitas Snapshot</dt>
            <dd>{{ $storage->kapasitas_snapshot !== null ? number_format((float) $storage->kapasitas_snapshot, 0, ',', '.') : '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>Status Replikasi</dt>
            <dd>{{ $storage->status_replikasi ?: '-' }}</dd>
        </div>
        <div class="storage-detail-item">
            <dt>IP Manajemen</dt>
            <dd>{{ $storage->alamat_ip_manajemen ?: '-' }}</dd>
        </div>
    </dl>
</div>
@endsection
