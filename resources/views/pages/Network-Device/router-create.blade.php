@extends('layouts.app')

@section('title', 'Tambah Router')

@section('page-title', 'Tambah Router')
@section('page-subtitle', 'Tambahkan informasi perangkat router')

@section('content')
<link rel="stylesheet" href="{{ asset('css/network-device.css') }}">

<div class="nd-back-wrapper">
    <a href="{{ route('network-device.router') }}" class="nd-back-btn">
        <span aria-hidden="true">←</span>
        Kembali
    </a>
</div>

<form action="{{ route('network-device.router-store') }}" method="POST" class="nd-edit-form">
    @csrf
    <h2 class="nd-edit-title">Tambah Router Baru</h2>

    @if($errors->any())
    <div class="nd-alert nd-alert-error">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="nd-edit-grid">
        <div class="nd-edit-field">
            <label for="jenis_router">Jenis Router</label>
            <select id="jenis_router" name="jenis_router" required>
                @foreach(['Core', 'Edge', 'Branch'] as $type)
                    <option value="{{ $type }}" @selected(old('jenis_router') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="nd-edit-field">
            <label for="kapasitas_bandwidth">Kapasitas Bandwidth (Mbps)</label>
            <input id="kapasitas_bandwidth" name="kapasitas_bandwidth" type="number" min="0" step="any" value="{{ old('kapasitas_bandwidth') }}">
        </div>
        <div class="nd-edit-field">
            <label for="protokol_routing">Protokol Routing</label>
            <input id="protokol_routing" name="protokol_routing" type="text" value="{{ old('protokol_routing') }}">
        </div>
        <div class="nd-edit-field">
            <label for="jumlah_tunnel_vpn">Jumlah Tunnel VPN</label>
            <input id="jumlah_tunnel_vpn" name="jumlah_tunnel_vpn" type="number" min="0" value="{{ old('jumlah_tunnel_vpn') }}">
        </div>
        <div class="nd-edit-field">
            <label for="status_vpn">Status VPN</label>
            <input id="status_vpn" name="status_vpn" type="text" value="{{ old('status_vpn') }}">
        </div>
        <div class="nd-edit-field">
            <label for="status_nat">Status NAT</label>
            <input id="status_nat" name="status_nat" type="text" value="{{ old('status_nat') }}">
        </div>
        <div class="nd-edit-field">
            <label for="status_ha">Status HA</label>
            <input id="status_ha" name="status_ha" type="text" value="{{ old('status_ha') }}">
        </div>
        <div class="nd-edit-field">
            <label for="alamat_ip_manajemen">IP Manajemen</label>
            <input id="alamat_ip_manajemen" name="alamat_ip_manajemen" type="text" value="{{ old('alamat_ip_manajemen') }}">
        </div>
    </div>

    <div class="nd-edit-actions">
        <a href="{{ route('network-device.router') }}" class="nd-btn-cancel">Batal</a>
        <button type="submit" class="nd-btn-save">Simpan Router</button>
    </div>
</form>
@endsection
