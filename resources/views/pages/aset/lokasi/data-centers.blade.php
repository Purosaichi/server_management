@extends('layouts.app')

@section('title', 'Data Center')
@section('page-title', 'Data Center')
@section('page-subtitle', 'Daftar pusat data yang terdaftar')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-location.css') }}">

@if(session('success'))
    <div class="asset-location-alert">{{ session('success') }}</div>
@endif

<div class="asset-location-page">
    <div class="asset-location-header">
        <div class="asset-location-heading">
            <span class="asset-location-mark" aria-hidden="true"><i class="fas fa-server"></i></span>
            <div>
                <span class="asset-location-eyebrow">Inventaris Infrastruktur</span>
                <h1 class="asset-location-title">Data Center</h1>
                <p class="asset-location-subtitle">Daftar pusat data yang terdaftar</p>
            </div>
        </div>
        <div class="asset-location-header-actions">
            <div class="asset-location-count" aria-label="Jumlah Data Center">
                <i class="fas fa-map-pin" aria-hidden="true"></i>
                <strong>{{ $dataCenters->count() }}</strong>
                <span>data center</span>
            </div>
            <a href="{{ route('aset.data-centers.create') }}" class="asset-location-add-button">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Tambah Data Center
            </a>
        </div>
    </div>

    <nav class="asset-location-tabs" aria-label="Subhalaman Lokasi Aset">
        <a href="{{ route('aset.lokasi') }}">
            <i class="fas fa-location-dot" aria-hidden="true"></i>
            Lokasi Aset
        </a>
        <a href="{{ route('aset.data-centers') }}" class="active" aria-current="page">
            <i class="fas fa-server" aria-hidden="true"></i>
            Data Center
        </a>
    </nav>

    <div class="asset-location-table-wrap">
        <table class="asset-location-table">
            <thead>
                <tr>
                    <th>Nama Data Center</th>
                    <th>Lokasi</th>
                    <th>Tipe</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse($dataCenters as $dataCenter)
                    <tr class="hover:bg-gray-50">
                        <td class="asset-location-center">{{ $dataCenter->nama_data_center }}</td>
                        <td>{{ $dataCenter->lokasi ?: '-' }}</td>
                        <td>{{ $dataCenter->tipe_dc ?: '-' }}</td>
                        <td class="asset-location-notes" title="{{ $dataCenter->keterangan }}">{{ $dataCenter->keterangan ?: '-' }}</td>
                        <td>
                            <a href="{{ route('aset.data-centers.edit', $dataCenter->id_data_center) }}" class="asset-location-edit-button">
                                <i class="fas fa-pen" aria-hidden="true"></i>
                                Edit
                            </a>
                            <form action="{{ route('aset.data-centers.destroy', $dataCenter->id_data_center) }}" method="POST" class="asset-location-delete-form" onsubmit="return confirm('Yakin ingin menghapus Data Center ini?')">
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
                    <tr><td colspan="5" class="asset-location-empty">Belum ada data center.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection