@extends('layouts.app')

@section('title', 'Tambah Storage')
@section('page-title', 'Tambah Storage')
@section('page-subtitle', 'Tambahkan perangkat storage ke inventaris aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/storage.css') }}">

<div class="storage-back-wrapper">
    <a href="{{ route('aset.storage') }}" class="storage-back-button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>
</div>

<div class="storage-form-page">
    <div class="storage-form-header">
        <div>
            <h1>Tambah Storage</h1>
            <p>Lengkapi aset dan spesifikasi perangkat storage.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="storage-form-errors" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('aset.storage.store') }}" method="POST" class="storage-form">
        @csrf

        <h2 class="storage-form-section-title">Informasi Aset</h2>
        <div class="storage-form-grid">
            <label class="storage-form-field storage-form-field-wide">
                <span>Aset <strong>*</strong></span>
                <select name="id_aset" required>
                    <option value="">Pilih aset</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id_aset }}" @selected(old('id_aset') == $asset->id_aset)>
                            {{ $asset->kode_aset }} - {{ $asset->nama_aset }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label class="storage-form-field">
                <span>Jenis Storage</span>
                <input type="text" name="jenis_storage" value="{{ old('jenis_storage') }}" maxlength="50" placeholder="Contoh: SAN">
            </label>
            <label class="storage-form-field">
                <span>IP Manajemen</span>
                <input type="text" name="alamat_ip_manajemen" value="{{ old('alamat_ip_manajemen') }}" maxlength="45" placeholder="Contoh: 192.0.2.10">
            </label>
        </div>

        <h2 class="storage-form-section-title">Kapasitas</h2>
        <p class="storage-form-hint">Kapasitas tersedia dan persentase penggunaan dihitung otomatis dari nilai raw dan terpakai.</p>
        <div class="storage-form-grid">
            <label class="storage-form-field">
                <span>Kapasitas Raw</span>
                <input type="number" name="kapasitas_raw" value="{{ old('kapasitas_raw') }}" min="0" step="1">
            </label>
            <label class="storage-form-field">
                <span>Kapasitas Terpakai</span>
                <input type="number" name="kapasitas_terpakai" value="{{ old('kapasitas_terpakai') }}" min="0" step="1">
            </label>
        </div>

        <h2 class="storage-form-section-title">Konfigurasi Disk</h2>
        <div class="storage-form-grid">
            <label class="storage-form-field">
                <span>Jenis Disk</span>
                <input type="text" name="jenis_disk" value="{{ old('jenis_disk') }}" maxlength="50" placeholder="Contoh: SSD">
            </label>
            <label class="storage-form-field">
                <span>Jumlah Disk</span>
                <input type="number" name="jumlah_disk" value="{{ old('jumlah_disk') }}" min="0" step="1">
            </label>
            <label class="storage-form-field">
                <span>Tingkat RAID</span>
                <input type="text" name="tingkat_raid" value="{{ old('tingkat_raid') }}" maxlength="10" placeholder="Contoh: RAID 5">
            </label>
            <label class="storage-form-field">
                <span>Jumlah Pengendali</span>
                <input type="number" name="jumlah_pengendali" value="{{ old('jumlah_pengendali') }}" min="0" step="1">
            </label>
            <label class="storage-form-field storage-form-field-wide">
                <span>Protokol Storage</span>
                <input type="text" name="protokol_storage" value="{{ old('protokol_storage') }}" maxlength="50" placeholder="Contoh: iSCSI">
            </label>
        </div>

        <h2 class="storage-form-section-title">Volume & Replikasi</h2>
        <div class="storage-form-grid">
            <label class="storage-form-field">
                <span>Jumlah Volume</span>
                <input type="number" name="jumlah_volume" value="{{ old('jumlah_volume') }}" min="0" step="1">
            </label>
            <label class="storage-form-field">
                <span>Jumlah LUN</span>
                <input type="number" name="jumlah_lun" value="{{ old('jumlah_lun') }}" min="0" step="1">
            </label>
            <label class="storage-form-field">
                <span>Kapasitas Snapshot</span>
                <input type="number" name="kapasitas_snapshot" value="{{ old('kapasitas_snapshot') }}" min="0" step="1">
            </label>
            <label class="storage-form-field">
                <span>Status Replikasi</span>
                <input type="text" name="status_replikasi" value="{{ old('status_replikasi') }}" maxlength="50" placeholder="Contoh: Aktif">
            </label>
        </div>

        <div class="storage-form-actions">
            <a href="{{ route('aset.storage') }}" class="storage-cancel-button">Batal</a>
            <button type="submit" class="storage-add-button">
                <i class="fas fa-floppy-disk" aria-hidden="true"></i>
                Simpan Storage
            </button>
        </div>
    </form>
</div>
@endsection
