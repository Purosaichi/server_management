@extends('layouts.app')

@section('title', 'Detail Lokasi Aset')
@section('page-title', 'Detail Lokasi Aset')
@section('page-subtitle', 'Informasi lengkap lokasi penempatan aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-location.css') }}">

<div class="asset-location-form-page">
    <div class="asset-location-form-header">
        <div>
            <h1>Detail Lokasi Aset</h1>
            <p>{{ $location->nama_aset ?: 'Aset' }}{{ $location->kode_aset ? ' - ' . $location->kode_aset : '' }}</p>
        </div>
        <a href="{{ route('aset.lokasi') }}" class="asset-location-back-link">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            Kembali
        </a>
    </div>

    <dl class="asset-location-detail-grid">
        <div class="asset-location-detail-item">
            <dt>Data Center</dt>
            <dd>{{ $location->nama_data_center ?: '-' }}</dd>
        </div>
        <div class="asset-location-detail-item">
            <dt>Gedung</dt>
            <dd>{{ $location->nama_gedung ?: '-' }}</dd>
        </div>
        <div class="asset-location-detail-item">
            <dt>Lantai</dt>
            <dd>{{ $location->lantai ?: '-' }}</dd>
        </div>
        <div class="asset-location-detail-item">
            <dt>Ruangan</dt>
            <dd>{{ $location->nama_ruangan ?: '-' }}</dd>
        </div>
        <div class="asset-location-detail-item">
            <dt>Zona</dt>
            <dd>{{ $location->zona ?: '-' }}</dd>
        </div>
        <div class="asset-location-detail-item">
            <dt>Kode Rak</dt>
            <dd>{{ $location->kode_rak ?: '-' }}</dd>
        </div>
        <div class="asset-location-detail-item">
            <dt>Nama Rak</dt>
            <dd>{{ $location->nama_rak ?: '-' }}</dd>
        </div>
        <div class="asset-location-detail-item">
            <dt>Tinggi Rak</dt>
            <dd>{{ $location->tinggi_rak ? $location->tinggi_rak . 'U' : '-' }}</dd>
        </div>
        <div class="asset-location-detail-item">
            <dt>Posisi Rak</dt>
            <dd>{{ $location->posisi_rak ?: '-' }}</dd>
        </div>
        <div class="asset-location-detail-item">
            <dt>Tanggal Penempatan</dt>
            <dd>{{ $location->tanggal_penempatan ? \Carbon\Carbon::parse($location->tanggal_penempatan)->format('d M Y') : '-' }}</dd>
        </div>
        <div class="asset-location-detail-item asset-location-detail-item-wide">
            <dt>Keterangan</dt>
            <dd>{{ $location->keterangan ?: '-' }}</dd>
        </div>
    </dl>

    <div class="asset-location-form-actions">
        <a href="{{ route('aset.lokasi.edit', $location->id_lokasi_aset) }}" class="asset-location-add-button">
            <i class="fas fa-pen" aria-hidden="true"></i>
            Edit Lokasi
        </a>
    </div>
</div>
@endsection
