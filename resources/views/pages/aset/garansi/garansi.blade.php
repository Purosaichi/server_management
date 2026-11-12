@extends('layouts.app')

@section('title', 'Garansi')
@section('page-title', 'Garansi')
@section('page-subtitle', 'Daftar garansi aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-location.css') }}">

@if(session('success'))
    <div class="asset-location-alert" role="status">{{ session('success') }}</div>
@endif

<div class="asset-location-page">
    <div class="asset-location-header">
        <div class="asset-location-heading">
            <span class="asset-location-mark" aria-hidden="true"><i class="fas fa-shield-halved"></i></span>
            <div>
                <span class="asset-location-eyebrow">Inventaris Infrastruktur</span>
                <h1 class="asset-location-title">Garansi</h1>
                <p class="asset-location-subtitle">Daftar masa garansi aset dan server</p>
            </div>
        </div>
        <div class="asset-location-header-actions">
            <div class="asset-location-count" aria-label="Jumlah garansi">
                <i class="fas fa-shield-halved" aria-hidden="true"></i>
                <strong>{{ $warranties->count() }}</strong>
                <span>garansi</span>
            </div>
            <a href="{{ route('aset.garansi.create') }}" class="asset-location-add-button">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Tambah Garansi
            </a>
        </div>
    </div>

    <div class="asset-location-table-wrap">
        <table class="asset-location-table">
            <thead>
                <tr>
                    <th>Nomor Garansi</th>
                    <th>Aset / Server</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Berakhir</th>
                    <th>Tingkat Layanan</th>
                    <th>Tingkat SLA</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse($warranties as $warranty)
                    <tr class="hover:bg-gray-50">
                        <td>{{ $warranty->nomor_garansi ?: '-' }}</td>
                        <td>{{ $warranty->nama_server_by_id ?: ($warranty->nama_server_by_asset ?: ($warranty->nama_aset ?: '-')) }}</td>
                        <td>{{ $warranty->tanggal_mulai ? \Carbon\Carbon::parse($warranty->tanggal_mulai)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $warranty->tanggal_berakhir ? \Carbon\Carbon::parse($warranty->tanggal_berakhir)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $warranty->tingkat_layanan ?: '-' }}</td>
                        <td>{{ $warranty->tingkat_sla ?: '-' }}</td>
                        <td>
                            <span class="warranty-status-badge {{ $warranty->status_garansi === 'Aktif' ? 'warranty-status-active' : 'warranty-status-ended' }}">
                                {{ $warranty->status_garansi }}
                            </span>
                        </td>
                        <td>{{ $warranty->keterangan ?: '-' }}</td>
                        <td>
                            <a href="{{ route('aset.garansi.edit', $warranty->id_garansi) }}" class="asset-location-edit-button">
                                <i class="fas fa-pen" aria-hidden="true"></i>
                                Edit
                            </a>
                            <form action="{{ route('aset.garansi.destroy', $warranty->id_garansi) }}" method="POST"
                                class="asset-location-delete-form"
                                data-delete-confirm
                                data-delete-name="garansi {{ $warranty->nomor_garansi ?: '#' . $warranty->id_garansi }}">
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
                    <tr><td colspan="9" class="asset-location-empty">Belum ada data garansi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('components.delete-confirm-modal')
@endsection
