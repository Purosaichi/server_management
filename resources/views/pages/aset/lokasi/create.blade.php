@extends('layouts.app')

@section('title', 'Tambah Lokasi Aset')
@section('page-title', 'Tambah Lokasi Aset')
@section('page-subtitle', 'Tambahkan informasi lokasi penempatan aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-location.css') }}">

<div class="asset-location-form-page">
    <div class="asset-location-form-header">
        <div>
            <h1>Tambah Lokasi Aset</h1>
            <p>Lengkapi informasi aset dan penempatannya.</p>
        </div>
        <a href="{{ route('aset.lokasi') }}" class="asset-location-back-link">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="asset-location-alert asset-location-alert-error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('aset.lokasi.store') }}" method="POST" class="asset-location-form">
        @csrf
        <div class="asset-location-form-grid">
            <label class="asset-location-field">
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
            <label class="asset-location-field">
                <span>Data Center</span>
                <select name="id_data_center">
                    <option value="">Pilih Data Center</option>
                    @foreach($dataCenters as $dataCenter)
                        <option value="{{ $dataCenter->id_data_center }}" @selected(old('id_data_center') == $dataCenter->id_data_center)>
                            {{ $dataCenter->nama_data_center }}{{ $dataCenter->lokasi ? ' - ' . $dataCenter->lokasi : '' }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label class="asset-location-field">
                <span>Gedung</span>
                <input type="text" name="nama_gedung" value="{{ old('nama_gedung') }}" maxlength="200">
            </label>
            <label class="asset-location-field">
                <span>Lantai</span>
                <input type="text" name="lantai" value="{{ old('lantai') }}" maxlength="40">
            </label>
            <label class="asset-location-field">
                <span>Ruangan</span>
                <input type="text" name="nama_ruangan" value="{{ old('nama_ruangan') }}" maxlength="200">
            </label>
            <label class="asset-location-field">
                <span>Zona</span>
                <input type="text" name="zona" value="{{ old('zona') }}" maxlength="100">
            </label>
            <label class="asset-location-field">
                <span>Kode Rak</span>
                <input type="text" name="kode_rak" value="{{ old('kode_rak') }}" maxlength="100">
            </label>
            <label class="asset-location-field">
                <span>Nama Rak</span>
                <input type="text" name="nama_rak" value="{{ old('nama_rak') }}" maxlength="200">
            </label>
            <label class="asset-location-field">
                <span>Tinggi Rak (U)</span>
                <input type="number" name="tinggi_rak" value="{{ old('tinggi_rak') }}" min="0" step="1">
            </label>
            <label class="asset-location-field">
                <span>Posisi Rak</span>
                <input type="text" name="posisi_rak" value="{{ old('posisi_rak') }}" maxlength="100">
            </label>
            <label class="asset-location-field">
                <span>Tanggal Penempatan</span>
                <input type="date" name="tanggal_penempatan" value="{{ old('tanggal_penempatan') }}">
            </label>
            <label class="asset-location-field asset-location-field-wide">
                <span>Keterangan</span>
                <textarea name="keterangan" rows="3" maxlength="510">{{ old('keterangan') }}</textarea>
            </label>
        </div>

        <div class="asset-location-form-actions">
            <a href="{{ route('aset.lokasi') }}" class="asset-location-cancel-button">Batal</a>
            <button type="submit" class="asset-location-add-button">
                <i class="fas fa-floppy-disk" aria-hidden="true"></i>
                Simpan Lokasi
            </button>
        </div>
    </form>
</div>
@endsection