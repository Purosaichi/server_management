@extends('layouts.app')

@section('title', 'Detail Storage')
@section('page-title', 'Detail Storage')
@section('page-subtitle', 'Informasi lengkap perangkat storage')

@section('content')
<link rel="stylesheet" href="{{ asset('css/storage.css') }}">

@php
    $usagePercentage = min(max((float) ($storage->persentase_penggunaan ?? 0), 0), 100);
    $usageLevel = $usagePercentage >= 90 ? 'is-critical' : ($usagePercentage >= 75 ? 'is-warning' : 'is-healthy');
@endphp

<div class="storage-back-wrapper">
    <a href="{{ route('aset.storage') }}" class="storage-back-button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>
</div>

@if(session('success'))
    <div class="storage-success-alert" role="status">{{ session('success') }}</div>
@endif

<div class="storage-page">
    <div class="storage-detail-header">
        <div class="storage-heading">
            <span class="storage-mark" aria-hidden="true"><i class="fas fa-hard-drive"></i></span>
            <div>
                <span class="storage-eyebrow">Inventaris Infrastruktur</span>
                <h1 class="storage-title">{{ $storage->nama_aset ?: 'Detail Storage' }}</h1>
                <p class="storage-subtitle">{{ $storage->kode_aset ?: 'Aset ID ' . $storage->id_aset }}</p>
            </div>
        </div>
        <div class="storage-detail-header-meta">
            <span class="storage-id-badge">Storage #{{ $storage->id_storage }}</span>
            <span class="storage-type-badge">{{ $storage->jenis_storage ?: 'Jenis tidak ditentukan' }}</span>
            <a href="{{ route('aset.storage.edit', $storage->id_storage) }}" class="storage-add-button">
                <i class="fas fa-pen" aria-hidden="true"></i>
                Edit Storage
            </a>
            <form action="{{ route('aset.storage.destroy', $storage->id_storage) }}" method="POST" class="storage-delete-form" onsubmit="return confirm('Yakin ingin menghapus data storage ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="storage-delete-button">
                    <i class="fas fa-trash" aria-hidden="true"></i>
                    Hapus Storage
                </button>
            </form>
        </div>
    </div>

    <section class="storage-capacity-panel" aria-labelledby="storage-capacity-title">
        <div class="storage-capacity-heading">
            <div>
                <span class="storage-section-eyebrow">Kapasitas</span>
                <h2 id="storage-capacity-title">Ringkasan penggunaan</h2>
            </div>
            @if($storage->persentase_penggunaan !== null)
                <div class="storage-usage-ring {{ $usageLevel }}"
                     style="--storage-usage: {{ $usagePercentage }}%"
                     role="progressbar"
                     aria-label="Penggunaan kapasitas storage"
                     aria-valuenow="{{ $usagePercentage }}"
                     aria-valuemin="0"
                     aria-valuemax="100">
                    <span>{{ number_format((float) $storage->persentase_penggunaan, 2, ',', '.') }}%</span>
                    <small>terpakai</small>
                </div>
            @else
                <span class="storage-no-usage">Persentase penggunaan belum tersedia</span>
            @endif
        </div>

        <div class="storage-capacity-stats">
            <article class="storage-capacity-stat">
                <span><i class="fas fa-database" aria-hidden="true"></i> Kapasitas Raw</span>
                <strong>{{ $storage->kapasitas_raw !== null ? number_format((float) $storage->kapasitas_raw, 0, ',', '.') : '-' }}</strong>
            </article>
            <article class="storage-capacity-stat is-used">
                <span><i class="fas fa-chart-pie" aria-hidden="true"></i> Kapasitas Terpakai</span>
                <strong>{{ $storage->kapasitas_terpakai !== null ? number_format((float) $storage->kapasitas_terpakai, 0, ',', '.') : '-' }}</strong>
            </article>
            <article class="storage-capacity-stat is-available">
                <span><i class="fas fa-circle-check" aria-hidden="true"></i> Kapasitas Tersedia</span>
                <strong>{{ $storage->kapasitas_tersedia !== null ? number_format((float) $storage->kapasitas_tersedia, 0, ',', '.') : '-' }}</strong>
            </article>
        </div>
    </section>

    <div class="storage-detail-sections">
        <section class="storage-detail-section" aria-labelledby="storage-disk-title">
            <header>
                <span class="storage-detail-section-icon"><i class="fas fa-server" aria-hidden="true"></i></span>
                <div>
                    <h2 id="storage-disk-title">Konfigurasi Disk</h2>
                    <p>Jenis dan susunan media penyimpanan</p>
                </div>
            </header>
            <dl class="storage-detail-grid">
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
            </dl>
        </section>

        <section class="storage-detail-section" aria-labelledby="storage-volume-title">
            <header>
                <span class="storage-detail-section-icon"><i class="fas fa-layer-group" aria-hidden="true"></i></span>
                <div>
                    <h2 id="storage-volume-title">Volume & Replikasi</h2>
                    <p>Alokasi logis dan perlindungan data</p>
                </div>
            </header>
            <dl class="storage-detail-grid">
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
            </dl>
        </section>

        <section class="storage-detail-section storage-detail-section-network" aria-labelledby="storage-network-title">
            <header>
                <span class="storage-detail-section-icon"><i class="fas fa-network-wired" aria-hidden="true"></i></span>
                <div>
                    <h2 id="storage-network-title">Informasi Manajemen</h2>
                    <p>Alamat jaringan untuk mengelola perangkat</p>
                </div>
            </header>
            <dl class="storage-detail-grid">
                <div class="storage-detail-item">
                    <dt>IP Manajemen</dt>
                    <dd class="storage-detail-monospace">{{ $storage->alamat_ip_manajemen ?: '-' }}</dd>
                </div>
            </dl>
        </section>
    </div>
</div>
@endsection
