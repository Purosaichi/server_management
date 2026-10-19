@extends('layouts.app')

@section('title', 'Tambah Aplikasi')

@section('page-title', 'Tambah Aplikasi')
@section('page-subtitle', 'Form untuk menambahkan aplikasi baru')

@section('content')
<link rel="stylesheet" href="{{ asset('css/application.css') }}">

<div class="app-back-wrapper">
    <a href="{{ route('application.index') }}" class="app-back-btn">← Back</a>
</div>

<div class="app-form-container">
    <h2 class="app-form-title">Tambah Aplikasi Baru</h2>

    @if($errors->any())
    <div class="app-alert app-alert-error">
        <ul style="margin: 0; padding-left: 1.25rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('application.store') }}" method="POST">
        @csrf

        <h3 class="app-form-section">Informasi Aplikasi</h3>
        <div class="app-form-grid">
            <div class="app-form-group">
                <label for="kode_aplikasi">Kode Aplikasi <span style="color: red;">*</span></label>
                <input type="text" name="kode_aplikasi" id="kode_aplikasi" 
                       value="{{ old('kode_aplikasi') }}" 
                       placeholder="Contoh: App-015" required>
            </div>

            <div class="app-form-group">
                <label for="nama_aplikasi">Nama Aplikasi <span style="color: red;">*</span></label>
                <input type="text" name="nama_aplikasi" id="nama_aplikasi" 
                       value="{{ old('nama_aplikasi') }}" 
                       placeholder="Contoh: GTK - Bantuan" required>
            </div>

            <div class="app-form-group">
                <label for="kategori">Kategori</label>
                <input type="text" name="kategori" id="kategori" 
                       value="{{ old('kategori') }}" 
                       placeholder="Contoh: Internal">
            </div>

            <div class="app-form-group">
                <label for="versi">Versi</label>
                <input type="text" name="versi" id="versi" 
                       value="{{ old('versi') }}" 
                       placeholder="Contoh: 1.0">
            </div>

            <div class="app-form-group">
                <label for="status">Status <span style="color: red;">*</span></label>
                <select name="status" id="status" required>
                    <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Down" {{ old('status') == 'Down' ? 'selected' : '' }}>Down</option>
                    <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>

            <div class="app-form-group">
                <label for="id_server">Server</label>
                <select name="id_server" id="id_server">
                    <option value="">-- Pilih Server --</option>
                    @foreach($servers as $id => $nama)
                        <option value="{{ $id }}" {{ old('id_server') == $id ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="app-form-group">
                <label for="id_domain">Domain</label>
                <select name="id_domain" id="id_domain">
                    <option value="">-- Pilih Domain --</option>
                    @foreach($domains as $domain)
                        <option value="{{ $domain->id_domain }}" {{ old('id_domain') == $domain->id_domain ? 'selected' : '' }}>
                            {{ $domain->nama_domain }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="app-form-group">
                <label for="id_lisensi">Licenses</label>
                <select name="id_lisensi" id="id_lisensi">
                    <option value="">-- Pilih Lisensi --</option>
                    @foreach($licenses as $license)
                        <option value="{{ $license->id_lisensi }}"
                            {{ old('id_lisensi') == $license->id_lisensi ? 'selected' : '' }}>
                            {{ $license->nama_lisensi }}{{ $license->provider ? ' - ' . $license->provider : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="app-form-group">
                <label for="database_digunakan">Database</label>
                <input type="text" name="database_digunakan" id="database_digunakan" 
                       value="{{ old('database_digunakan') }}" 
                       placeholder="Contoh: MySQL 8.0">
            </div>

            <div class="app-form-group">
                <label for="sla_percent">SLA (%)</label>
                <input type="number" step="0.01" name="sla_percent" id="sla_percent" 
                       value="{{ old('sla_percent') }}" min="0" max="100"
                       placeholder="Contoh: 99.5">
            </div>

            <div class="app-form-group">
                <label for="uptime_percent">Uptime (%)</label>
                <input type="number" step="0.01" name="uptime_percent" id="uptime_percent" 
                       value="{{ old('uptime_percent') }}" min="0" max="100"
                       placeholder="Contoh: 99.8">
            </div>

            <div class="app-form-group">
                <label for="tanggal_mulai_operasi">Tanggal Mulai Operasi</label>
                <input type="date" name="tanggal_mulai_operasi" id="tanggal_mulai_operasi" 
                       value="{{ old('tanggal_mulai_operasi') }}">
            </div>
        </div>

        <div class="app-form-group" style="margin-top: 1rem;">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="3" 
                      placeholder="Deskripsi aplikasi...">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="app-form-actions">
            <a href="{{ route('application.index') }}" class="app-btn-cancel">Batal</a>
            <button type="submit" class="app-btn-submit">Simpan Aplikasi</button>
        </div>
    </form>
</div>
@endsection