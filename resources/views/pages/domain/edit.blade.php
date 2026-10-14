@extends('layouts.app')

@section('title', 'Edit Domain')

@section('page-title', 'Edit Domain')
@section('page-subtitle', 'Perbarui informasi domain')

@section('content')
<div class="app-back-wrapper">
    <a href="{{ route('domain.domain') }}" class="app-back-btn">← Back</a>
</div>

<div class="app-form-container">
    <h2 class="app-form-title">Edit Domain</h2>

    @if($errors->any())
    <div class="app-alert app-alert-error">
        <ul style="margin: 0; padding-left: 1.25rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('domain.update', $domain->id_domain) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="app-form-grid">
            <div class="app-form-group">
                <label for="nama_domain">Nama Domain <span style="color: red;">*</span></label>
                <input type="text" name="nama_domain" id="nama_domain" value="{{ old('nama_domain', $domain->nama_domain) }}" placeholder="Contoh: example.com" required>
            </div>

            <div class="app-form-group">
                <label for="id_aplikasi">Aplikasi</label>
                <select name="id_aplikasi" id="id_aplikasi">
                    <option value="">-- Pilih Aplikasi --</option>
                    @foreach($aplikasis as $aplikasi)
                        <option value="{{ $aplikasi->id_aplikasi }}" {{ old('id_aplikasi', $domain->id_aplikasi) == $aplikasi->id_aplikasi ? 'selected' : '' }}>
                            {{ $aplikasi->nama_aplikasi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="app-form-group">
                <label for="status">Status <span style="color: red;">*</span></label>
                <select name="status" id="status" required>
                    <option value="Aktif" {{ old('status', $domain->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ old('status', $domain->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="app-form-group">
                <label for="registrar">Registrar</label>
                <input type="text" name="registrar" id="registrar" value="{{ old('registrar', $domain->registrar) }}" placeholder="Contoh: Namecheap">
            </div>

            <div class="app-form-group">
                <label for="tanggal_pembelian">Tanggal Pembelian</label>
                <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" value="{{ old('tanggal_pembelian', $domain->tanggal_pembelian) }}">
            </div>

            <div class="app-form-group">
                <label for="tanggal_kadaluarsa">Tanggal Kadaluarsa</label>
                <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa', $domain->tanggal_kadaluarsa) }}">
            </div>

            <div class="app-form-group">
                <label for="id_pic">PIC</label>
                <select name="id_pic" id="id_pic">
                    <option value="">-- Pilih PIC --</option>
                    @foreach($pics as $id => $nama)
                        <option value="{{ $id }}" {{ old('id_pic', $domain->id_pic) == $id ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="app-form-group" style="display: flex; align-items: center; gap: 0.75rem; padding-top: 2rem;">
                <input type="checkbox" name="perpanjangan_otomatis" id="perpanjangan_otomatis" value="1" {{ old('perpanjangan_otomatis', $domain->perpanjangan_otomatis) ? 'checked' : '' }}>
                <label for="perpanjangan_otomatis">Perpanjangan Otomatis</label>
            </div>
        </div>

        <div class="app-form-actions">
            <a href="{{ route('domain.domain') }}" class="app-btn-cancel">Batal</a>
            <button type="submit" class="app-btn-submit">Update Domain</button>
        </div>
    </form>
</div>
@endsection
