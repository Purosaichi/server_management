@extends('layouts.app')

@section('title', 'Edit Penyedia')
@section('page-title', 'Edit Penyedia')
@section('page-subtitle', 'Perbarui informasi perusahaan/vendor penyedia aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-location.css') }}">

<div class="asset-location-form-page">
    <div class="asset-location-form-header">
        <div>
            <h1>Edit Penyedia</h1>
            <p>Perbarui informasi penyedia aset.</p>
        </div>
        <a href="{{ route('aset.penyedia') }}" class="asset-location-back-link">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="asset-location-alert asset-location-alert-error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('aset.penyedia.update', $provider->id_penyedia) }}" method="POST" class="asset-location-form">
        @csrf
        @method('PUT')
        <div class="asset-location-form-grid">
            <label class="asset-location-field asset-location-field-wide">
                <span>Nama Penyedia <strong>*</strong></span>
                <input type="text" name="nama_penyedia" value="{{ old('nama_penyedia', $provider->nama_penyedia) }}" maxlength="255" required autofocus>
            </label>
            <label class="asset-location-field asset-location-field-wide">
                <span>Alamat</span>
                <textarea name="alamat" rows="3">{{ old('alamat', $provider->alamat) }}</textarea>
            </label>
            <label class="asset-location-field">
                <span>Telepon</span>
                <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $provider->nomor_telepon) }}" maxlength="50">
            </label>
            <label class="asset-location-field">
                <span>Email</span>
                <input type="email" name="alamat_email" value="{{ old('alamat_email', $provider->alamat_email) }}" maxlength="255">
            </label>
            <label class="asset-location-field">
                <span>Nama PIC</span>
                <input type="text" name="nama_kontak" value="{{ old('nama_kontak', $provider->nama_kontak) }}" maxlength="255">
            </label>
            <label class="asset-location-field asset-location-field-wide">
                <span>Catatan</span>
                <textarea name="keterangan" rows="3">{{ old('keterangan', $provider->keterangan) }}</textarea>
            </label>
        </div>

        <div class="asset-location-form-actions">
            <a href="{{ route('aset.penyedia') }}" class="asset-location-cancel-button">Batal</a>
            <button type="submit" class="asset-location-add-button">
                <i class="fas fa-floppy-disk" aria-hidden="true"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
