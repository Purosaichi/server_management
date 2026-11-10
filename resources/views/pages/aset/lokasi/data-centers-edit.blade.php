@extends('layouts.app')

@section('title', 'Edit Data Center')
@section('page-title', 'Edit Data Center')
@section('page-subtitle', 'Perbarui informasi pusat data')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-location.css') }}">

<div class="asset-location-form-page">
    <div class="asset-location-form-header">
        <div>
            <h1>Edit Data Center</h1>
            <p>Perbarui informasi pusat data.</p>
        </div>
        <a href="{{ route('aset.data-centers') }}" class="asset-location-back-link">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="asset-location-alert asset-location-alert-error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('aset.data-centers.update', $dataCenter->id_data_center) }}" method="POST" class="asset-location-form">
        @csrf
        @method('PUT')
        <div class="asset-location-form-grid">
            <label class="asset-location-field asset-location-field-wide">
                <span>Nama Data Center <strong>*</strong></span>
                <input type="text" name="nama_data_center" value="{{ old('nama_data_center', $dataCenter->nama_data_center) }}" maxlength="200" required autofocus>
            </label>
            <label class="asset-location-field">
                <span>Lokasi</span>
                <input type="text" name="lokasi" value="{{ old('lokasi', $dataCenter->lokasi) }}" maxlength="300">
            </label>
            <label class="asset-location-field">
                <span>Tipe Data Center</span>
                <select name="tipe_dc">
                    <option value="">Pilih tipe Data Center</option>
                    <option value="Primary" @selected(old('tipe_dc', $dataCenter->tipe_dc) === 'Primary')>Primary</option>
                    <option value="DRP" @selected(old('tipe_dc', $dataCenter->tipe_dc) === 'DRP')>DRP</option>
                    <option value="Cloud" @selected(old('tipe_dc', $dataCenter->tipe_dc) === 'Cloud')>Cloud</option>
                </select>
            </label>
            <label class="asset-location-field asset-location-field-wide">
                <span>Keterangan</span>
                <textarea name="keterangan" rows="3" maxlength="510">{{ old('keterangan', $dataCenter->keterangan) }}</textarea>
            </label>
        </div>

        <div class="asset-location-form-actions">
            <a href="{{ route('aset.data-centers') }}" class="asset-location-cancel-button">Batal</a>
            <button type="submit" class="asset-location-add-button">
                <i class="fas fa-floppy-disk" aria-hidden="true"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
