@extends('layouts.app')

@section('title', 'Storage')
@section('page-title', 'Storage')
@section('page-subtitle', 'Daftar perangkat storage dan kapasitas penyimpanan aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/storage.css') }}">

<div class="storage-page">
    <div class="storage-header">
        <div class="storage-heading">
            <span class="storage-mark" aria-hidden="true"><i class="fas fa-hard-drive"></i></span>
            <div>
                <span class="storage-eyebrow">Inventaris Infrastruktur</span>
                <h1 class="storage-title">Storage</h1>
                <p class="storage-subtitle">Daftar perangkat storage dan kapasitas penyimpanan aset</p>
            </div>
        </div>
        <div class="storage-header-actions">
            <div class="storage-count" aria-label="Jumlah perangkat storage">
                <i class="fas fa-hard-drive" aria-hidden="true"></i>
                <strong>{{ $storageCount }}</strong>
                <span>perangkat</span>
            </div>
        </div>
    </div>

    <nav class="storage-tabs" aria-label="Subhalaman Aset">
        <a href="{{ route('aset.lokasi') }}">
            <i class="fas fa-location-dot" aria-hidden="true"></i>
            Lokasi Aset
        </a>
        <a href="{{ route('aset.data-centers') }}">
            <i class="fas fa-server" aria-hidden="true"></i>
            Data Center
        </a>
        <a href="{{ route('aset.storage') }}" class="active" aria-current="page">
            <i class="fas fa-hard-drive" aria-hidden="true"></i>
            Storage
        </a>
    </nav>

    <section class="storage-summary" aria-label="Ringkasan kapasitas storage">
        <article class="storage-summary-card">
            <span>Total Kapasitas Raw</span>
            <strong>{{ number_format((float) $totalRawCapacity, 0, ',', '.') }}</strong>
        </article>
        <article class="storage-summary-card">
            <span>Total Kapasitas Terpakai</span>
            <strong>{{ number_format((float) $totalUsedCapacity, 0, ',', '.') }}</strong>
        </article>
        <article class="storage-summary-card">
            <span>Total Kapasitas Tersedia</span>
            <strong>{{ number_format((float) $totalAvailableCapacity, 0, ',', '.') }}</strong>
        </article>
    </section>

    <div class="storage-table-wrap">
        <table class="storage-table">
            <thead>
                <tr>
                    <th>ID Storage</th>
                    <th>Aset</th>
                    <th>Jenis Storage</th>
                    <th>Kapasitas Raw</th>
                    <th>Kapasitas Terpakai</th>
                    <th>Kapasitas Tersedia</th>
                    <th>Penggunaan</th>
                    <th>Jenis Disk</th>
                    <th>Jumlah Disk</th>
                    <th>Tingkat RAID</th>
                    <th>Pengendali</th>
                    <th>Protokol</th>
                    <th>Volume</th>
                    <th>LUN</th>
                    <th>Kapasitas Snapshot</th>
                    <th>Status Replikasi</th>
                    <th>IP Manajemen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($storageDevices as $storage)
                    @php
                        $usagePercentage = min(max((float) ($storage->persentase_penggunaan ?? 0), 0), 100);
                    @endphp
                    <tr>
                        <td>{{ $storage->id_storage }}</td>
                        <td>
                            <span class="storage-asset-name">{{ $storage->nama_aset ?: 'Aset tidak ditemukan' }}</span>
                            <span class="storage-asset-code">ID Aset: {{ $storage->id_aset }}</span>
                            <span class="storage-asset-code">{{ $storage->kode_aset ?: '-' }}</span>
                        </td>
                        <td>{{ $storage->jenis_storage ?: '-' }}</td>
                        <td>{{ $storage->kapasitas_raw !== null ? number_format((float) $storage->kapasitas_raw, 0, ',', '.') : '-' }}</td>
                        <td>{{ $storage->kapasitas_terpakai !== null ? number_format((float) $storage->kapasitas_terpakai, 0, ',', '.') : '-' }}</td>
                        <td>{{ $storage->kapasitas_tersedia !== null ? number_format((float) $storage->kapasitas_tersedia, 0, ',', '.') : '-' }}</td>
                        <td>
                            @if($storage->persentase_penggunaan !== null)
                                <div class="storage-usage" role="progressbar"
                                     aria-label="Penggunaan kapasitas"
                                     aria-valuenow="{{ $usagePercentage }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                    <span>{{ number_format((float) $storage->persentase_penggunaan, 2, ',', '.') }}%</span>
                                    <span class="storage-usage-track">
                                        <span class="storage-usage-fill" style="width: {{ $usagePercentage }}%"></span>
                                    </span>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $storage->jenis_disk ?: '-' }}</td>
                        <td>{{ $storage->jumlah_disk ?? '-' }}</td>
                        <td>{{ $storage->tingkat_raid ?: '-' }}</td>
                        <td>{{ $storage->jumlah_pengendali ?? '-' }}</td>
                        <td>{{ $storage->protokol_storage ?: '-' }}</td>
                        <td>{{ $storage->jumlah_volume ?? '-' }}</td>
                        <td>{{ $storage->jumlah_lun ?? '-' }}</td>
                        <td>{{ $storage->kapasitas_snapshot !== null ? number_format((float) $storage->kapasitas_snapshot, 0, ',', '.') : '-' }}</td>
                        <td>{{ $storage->status_replikasi ?: '-' }}</td>
                        <td>{{ $storage->alamat_ip_manajemen ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="17" class="storage-empty">Belum ada data storage.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection