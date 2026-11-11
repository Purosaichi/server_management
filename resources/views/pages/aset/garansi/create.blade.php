@extends('layouts.app')

@section('title', 'Tambah Garansi')
@section('page-title', 'Tambah Garansi')
@section('page-subtitle', 'Tambahkan data garansi aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-location.css') }}">

<div class="asset-location-form-page">
    <div class="asset-location-form-header">
        <div>
            <h1>Tambah Data Garansi</h1>
            <p>Lengkapi informasi garansi untuk aset yang dipilih.</p>
            <p>Status garansi ditentukan otomatis berdasarkan tanggal berakhir.</p>
        </div>
        <a href="{{ route('aset.garansi') }}" class="asset-location-back-link">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="asset-location-alert asset-location-alert-error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('aset.garansi.store') }}" method="POST" class="asset-location-form">
        @csrf
        <div class="asset-location-form-grid">
            <label class="asset-location-field">
                <span>Aset <strong>*</strong></span>
                <select name="id_aset" required>
                    <option value="">-- Pilih aset --</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id_aset }}" {{ (string) old('id_aset') === (string) $asset->id_aset ? 'selected' : '' }}>
                            {{ $asset->nama_aset }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label class="asset-location-field">
                <span>Nomor Garansi</span>
                <input type="text" name="nomor_garansi" value="{{ old('nomor_garansi') }}" maxlength="200">
            </label>
            <label class="asset-location-field">
                <span>Tanggal Mulai</span>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
            </label>
            <label class="asset-location-field">
                <span>Tanggal Berakhir</span>
                <input type="date" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}">
            </label>
            <label class="asset-location-field">
                <span>Tingkat Layanan</span>
                <input type="text" name="tingkat_layanan" value="{{ old('tingkat_layanan') }}" maxlength="100">
            </label>
            <label class="asset-location-field">
                <span>Tingkat SLA</span>
                <input type="text" name="tingkat_sla" value="{{ old('tingkat_sla') }}" maxlength="100">
            </label>
            <label class="asset-location-field asset-location-field-wide">
                <span>Keterangan</span>
                <textarea name="keterangan" rows="3" maxlength="510">{{ old('keterangan') }}</textarea>
            </label>
        </div>

        <div class="asset-location-form-actions">
            <a href="{{ route('aset.garansi') }}" class="asset-location-cancel-button">Batal</a>
            <button type="submit" class="asset-location-add-button">
                <i class="fas fa-floppy-disk" aria-hidden="true"></i>
                Simpan Garansi
            </button>
        </div>
    </form>
</div>
@endsection
