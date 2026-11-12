@extends('layouts.app')

@section('title', 'Lokasi Aset')
@section('page-title', 'Lokasi Aset')
@section('page-subtitle', 'Daftar lokasi penyimpanan dan penempatan aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-location.css') }}">

@if(session('success'))
    <div class="asset-location-alert">{{ session('success') }}</div>
@endif

<div class="asset-location-page">
    <div class="asset-location-header">
        <div class="asset-location-heading">
            <span class="asset-location-mark" aria-hidden="true"><i class="fas fa-location-dot"></i></span>
            <div>
                <span class="asset-location-eyebrow">Inventaris Infrastruktur</span>
                <h1 class="asset-location-title">Lokasi Aset</h1>
                <p class="asset-location-subtitle">Daftar lokasi penyimpanan dan penempatan aset</p>
            </div>
        </div>
        <div class="asset-location-header-actions">
            <div class="asset-location-count" aria-label="Jumlah data">
                <i class="fas fa-map-pin" aria-hidden="true"></i>
                <strong>{{ $locations->count() }}</strong>
                <span>lokasi terdaftar</span>
            </div>
            <a href="{{ route('aset.lokasi.create') }}" class="asset-location-add-button">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Tambah Lokasi
            </a>
        </div>
    </div>

    <nav class="asset-location-tabs" aria-label="Subhalaman Lokasi Aset">
        <a href="{{ route('aset.lokasi') }}" class="active" aria-current="page">
            <i class="fas fa-location-dot" aria-hidden="true"></i>
            Lokasi Aset
        </a>
        <a href="{{ route('aset.data-centers') }}">
            <i class="fas fa-server" aria-hidden="true"></i>
            Data Center
        </a>
    </nav>

    <div class="asset-location-table-wrap">
        <table class="asset-location-table">
            <thead>
                <tr>
                    <th>Data Center</th>
                    <th>Gedung</th>
                    <th>Ruangan</th>
                    <th>Zona</th>
                    <th>Nama Rak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse($locations as $location)
                    <tr class="asset-location-row hover:bg-gray-50"
                        data-detail-url="{{ route('aset.lokasi.detail', $location->id_lokasi_aset) }}"
                        title="Klik dua kali untuk melihat detail"
                        ondblclick="if (!event.target.closest('a, button, form')) window.location.href = this.dataset.detailUrl">
                        <td class="asset-location-center">{{ $location->nama_data_center ?: '-' }}</td>
                        <td>{{ $location->nama_gedung ?: '-' }}</td>
                        <td>{{ $location->nama_ruangan ?: '-' }}</td>
                        <td><span class="asset-location-zone">{{ $location->zona ?: '-' }}</span></td>
                        <td class="asset-location-rack-name">{{ $location->nama_rak ?: '-' }}</td>
                        <td>
                            <a href="{{ route('aset.lokasi.edit', $location->id_lokasi_aset) }}" class="asset-location-edit-button">
                                <i class="fas fa-pen" aria-hidden="true"></i>
                                Edit
                            </a>
                            <form action="{{ route('aset.lokasi.destroy', $location->id_lokasi_aset) }}" method="POST" class="asset-location-delete-form" data-delete-confirm data-delete-name="lokasi aset {{ $location->nama_rak ?: $location->nama_ruangan ?: '#' . $location->id_lokasi_aset }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="asset-location-delete-button">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="asset-location-empty">Belum ada data lokasi aset.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@include('components.delete-confirm-modal')
@endsection