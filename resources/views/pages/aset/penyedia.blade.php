@extends('layouts.app')

@section('title', 'Penyedia')
@section('page-title', 'Penyedia')
@section('page-subtitle', 'Daftar penyedia aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-location.css') }}">

<div class="asset-location-page">
    <div class="asset-location-header">
        <div class="asset-location-heading">
            <span class="asset-location-mark" aria-hidden="true"><i class="fas fa-user"></i></span>
            <div>
                <span class="asset-location-eyebrow">Inventaris Infrastruktur</span>
                <h1 class="asset-location-title">Penyedia</h1>
                <p class="asset-location-subtitle">Daftar perusahaan/vendor penyedia aset</p>
            </div>
        </div>
        <div class="asset-location-count" aria-label="Jumlah penyedia">
            <i class="fas fa-user" aria-hidden="true"></i>
            <strong>{{ $providers->count() }}</strong>
            <span>penyedia</span>
        </div>
    </div>

    <div class="asset-location-table-wrap">
        <table class="asset-location-table">
            <thead>
                <tr>
                    <th>ID Penyedia</th>
                    <th>Nama Penyedia</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Nama PIC</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse($providers as $provider)
                    <tr class="hover:bg-gray-50">
                        <td>{{ $provider->id_penyedia }}</td>
                        <td class="asset-location-center">{{ $provider->nama_penyedia }}</td>
                        <td>{{ $provider->alamat ?: '-' }}</td>
                        <td>{{ $provider->nomor_telepon ?: '-' }}</td>
                        <td>{{ $provider->alamat_email ?: '-' }}</td>
                        <td>{{ $provider->nama_kontak ?: '-' }}</td>
                        <td class="asset-location-notes" title="{{ $provider->keterangan }}">{{ $provider->keterangan ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="asset-location-empty">Belum ada data penyedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
