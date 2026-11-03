@extends('layouts.app')

@section('title', 'Edit Switch')

@section('page-title', 'Edit Switch')
@section('page-subtitle', 'Perbarui informasi perangkat switch')

@section('content')
<link rel="stylesheet" href="{{ asset('css/network-device.css') }}">

<div class="nd-back-wrapper">
    <a href="{{ route('network-device.switch') }}" class="nd-back-btn">
        <span aria-hidden="true">←</span>
        Kembali
    </a>
</div>

<form action="{{ route('network-device.switch-update', $switch->id_switch) }}" method="POST" class="nd-edit-form">
    @csrf
    @method('PUT')
    <h2 class="nd-edit-title">Edit Switch #{{ $switch->id_switch }}</h2>

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
            <label for="jenis_switch">Jenis Switch</label>
            <select id="jenis_switch" name="jenis_switch" required>
                @foreach(['Core', 'Distribution', 'Access'] as $type)
                    <option value="{{ $type }}" @selected(old('jenis_switch', $switch->jenis_switch) === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="nd-edit-field">
            <label for="lapisan_jaringan">Layer Jaringan</label>
            <input id="lapisan_jaringan" name="lapisan_jaringan" type="text" value="{{ old('lapisan_jaringan', $switch->lapisan_jaringan) }}">
        </div>
        <div class="nd-edit-field">
            <label for="jumlah_port">Total Port</label>
            <input id="jumlah_port" name="jumlah_port" type="number" min="0" value="{{ old('jumlah_port', $switch->jumlah_port) }}">
        </div>
        <div class="nd-edit-field">
            <label for="jumlah_port_terpakai">Port Terpakai</label>
            <input id="jumlah_port_terpakai" name="jumlah_port_terpakai" type="number" min="0" value="{{ old('jumlah_port_terpakai', $switch->jumlah_port_terpakai) }}">
        </div>
        <div class="nd-edit-field">
            <label for="jumlah_port_tersedia">Port Tersedia</label>
            <input id="jumlah_port_tersedia" name="jumlah_port_tersedia" type="number" min="0" value="{{ old('jumlah_port_tersedia', $switch->jumlah_port_tersedia) }}">
        </div>
        <div class="nd-edit-field">
            <label for="kecepatan_port">Kecepatan Port</label>
            <input id="kecepatan_port" name="kecepatan_port" type="text" value="{{ old('kecepatan_port', $switch->kecepatan_port) }}">
        </div>
        <div class="nd-edit-field">
            <label for="jumlah_port_uplink">Jumlah Port Uplink</label>
            <input id="jumlah_port_uplink" name="jumlah_port_uplink" type="number" min="0" value="{{ old('jumlah_port_uplink', $switch->jumlah_port_uplink) }}">
        </div>
        <div class="nd-edit-field">
            <label for="status_stack">Status Stack</label>
            <select id="status_stack" name="status_stack">
                <option value="">-- Pilih status --</option>
                @foreach(['Aktif', 'Tidak Aktif'] as $status)
                    <option value="{{ $status }}" @selected(old('status_stack', $switch->status_stack) === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="nd-edit-field">
            <label for="jumlah_anggota_stack">Anggota Stack</label>
            <input id="jumlah_anggota_stack" name="jumlah_anggota_stack" type="number" min="0" value="{{ old('jumlah_anggota_stack', $switch->jumlah_anggota_stack) }}">
        </div>
        <div class="nd-edit-field">
            <label for="jumlah_vlan">Jumlah VLAN</label>
            <input id="jumlah_vlan" name="jumlah_vlan" type="number" min="0" value="{{ old('jumlah_vlan', $switch->jumlah_vlan) }}">
        </div>
        <div class="nd-edit-field">
            <label for="alamat_ip_manajemen">IP Manajemen</label>
            <input id="alamat_ip_manajemen" name="alamat_ip_manajemen" type="text" value="{{ old('alamat_ip_manajemen', $switch->alamat_ip_manajemen) }}">
        </div>
        <div class="nd-edit-field">
            <label for="alamat_mac">MAC Address</label>
            <input id="alamat_mac" name="alamat_mac" type="text" value="{{ old('alamat_mac', $switch->alamat_mac) }}">
        </div>
    </div>

    <div class="nd-edit-actions">
        <a href="{{ route('network-device.switch') }}" class="nd-btn-cancel">Batal</a>
        <button type="submit" class="nd-btn-save">Simpan Perubahan</button>
    </div>
</form>
@endsection
