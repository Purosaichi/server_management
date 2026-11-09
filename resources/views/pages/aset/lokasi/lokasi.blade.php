@extends('layouts.app')

@section('title', 'Lokasi Aset')
@section('page-title', 'Lokasi Aset')
@section('page-subtitle', 'Daftar lokasi penyimpanan dan penempatan aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-location.css') }}">

<div class="asset-location-page">
    <div class="asset-location-header">
        <div>
            <h1 class="asset-location-title">Lokasi Aset</h1>
            <p class="asset-location-subtitle">Daftar lokasi penyimpanan dan penempatan aset</p>
        </div>
        <span class="asset-location-count">Total: {{ $locations->count() }} lokasi</span>
    </div>

    <div class="asset-location-table-wrap">
        <table class="asset-location-table">
            <thead>
                <tr>
                    <th class="px-4 py-3">ID Lokasi</th>
                    <th class="px-4 py-3">ID Aset</th>
                    <th class="px-4 py-3">Data Center</th>
                    <th class="px-4 py-3">Gedung</th>
                    <th class="px-4 py-3">Lantai</th>
                    <th class="px-4 py-3">Ruangan</th>
                    <th class="px-4 py-3">Zona</th>
                    <th class="px-4 py-3">Kode Rak</th>
                    <th class="px-4 py-3">Nama Rak</th>
                    <th class="px-4 py-3">Tinggi Rak</th>
                    <th class="px-4 py-3">Posisi Rak</th>
                    <th class="px-4 py-3">Tanggal Penempatan</th>
                    <th class="px-4 py-3">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $location)
                    <tr>
                        <td class="asset-location-id">{{ $location->id_lokasi_aset }}</td>
                        <td>{{ $location->id_aset }}</td>
                        <td>{{ $location->nama_data_center ?: '-' }}</td>
                        <td>{{ $location->nama_gedung ?: '-' }}</td>
                        <td>{{ $location->lantai ?: '-' }}</td>
                        <td>{{ $location->nama_ruangan ?: '-' }}</td>
                        <td>{{ $location->zona ?: '-' }}</td>
                        <td>{{ $location->kode_rak ?: '-' }}</td>
                        <td>{{ $location->nama_rak ?: '-' }}</td>
                        <td>{{ $location->tinggi_rak ?: '-' }}</td>
                        <td>{{ $location->posisi_rak ?: '-' }}</td>
                        <td>
                            {{ $location->tanggal_penempatan ? \Carbon\Carbon::parse($location->tanggal_penempatan)->format('d M Y') : '-' }}
                        </td>
                        <td class="asset-location-notes" title="{{ $location->keterangan }}">{{ $location->keterangan ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="asset-location-empty">
                            Belum ada data lokasi aset.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection