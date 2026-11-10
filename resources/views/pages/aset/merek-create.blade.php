@extends('layouts.app')

@section('title', 'Tambah Merek')
@section('page-title', 'Tambah Merek')
@section('page-subtitle', 'Tambahkan merek perangkat aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/merek.css') }}">

<div class="merek-form-page">
    <div class="merek-form-header">
        <div>
            <h1>Tambah Merek</h1>
            <p>Lengkapi informasi merek perangkat.</p>
        </div>
        <a href="{{ route('aset.merek') }}" class="merek-back-button">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="merek-alert merek-alert-error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('aset.merek.store') }}" method="POST" class="merek-form">
        @csrf
        <label class="merek-field">
            <span>Nama Merek <strong>*</strong></span>
            <input type="text" name="nama_merek" value="{{ old('nama_merek') }}" maxlength="255" required autofocus>
        </label>
        <label class="merek-field">
            <span>Keterangan</span>
            <textarea name="keterangan" rows="4">{{ old('keterangan') }}</textarea>
        </label>
        <div class="merek-form-actions">
            <a href="{{ route('aset.merek') }}" class="merek-cancel-button">Batal</a>
            <button type="submit" class="merek-add-button">
                <i class="fas fa-floppy-disk" aria-hidden="true"></i>
                Simpan Merek
            </button>
        </div>
    </form>
</div>
@endsection
