@extends('layouts.app')

@section('title', $license ? 'Edit Lisensi' : 'Tambah Lisensi')
@section('page-title', $license ? 'Edit Lisensi' : 'Tambah Lisensi')
@section('page-subtitle', 'Informasi lisensi, billing, dan aset terkait')

@section('content')
<link rel="stylesheet" href="{{ asset('css/application.css') }}">
<link rel="stylesheet" href="{{ asset('css/licenses.css') }}">

<div class="app-back-wrapper">
    <a href="{{ route('licenses.licenses') }}" class="app-back-btn">← Kembali</a>
</div>

<div class="app-form-container">
    <h2 class="app-form-title">{{ $license ? 'Edit Lisensi' : 'Tambah Lisensi' }}</h2>

    @if($errors->any())
        <div class="app-alert app-alert-error">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $license ? route('licenses.update', $license->id_lisensi) : route('licenses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($license)
            @method('PUT')
        @endif

        <h3 class="app-form-section">Informasi Lisensi</h3>
        <div class="app-form-grid">
            <div class="app-form-group">
                <label for="nama_lisensi">Nama Lisensi <span style="color: red;">*</span></label>
                <input type="text" id="nama_lisensi" name="nama_lisensi" value="{{ old('nama_lisensi', $license->nama_lisensi ?? '') }}" required maxlength="150">
            </div>
            <div class="app-form-group">
                <label for="provider">Provider <span style="color: red;">*</span></label>
                <input type="text" id="provider" name="provider" value="{{ old('provider', $license->provider ?? '') }}" required maxlength="100">
            </div>
            <div class="app-form-group">
                <label for="status">Status <span style="color: red;">*</span></label>
                <select id="status" name="status" required>
                    @foreach(['Aman', 'Akan Expired', 'Expired'] as $status)
                        <option value="{{ $status }}" {{ old('status', $license->status ?? 'Aman') === $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="app-form-group">
                <label for="jumlah_lisensi">Jumlah Lisensi <span style="color: red;">*</span></label>
                <input type="number" id="jumlah_lisensi" name="jumlah_lisensi" value="{{ old('jumlah_lisensi', $license->jumlah_lisensi ?? 1) }}" min="1" required>
            </div>
            <div class="app-form-group">
                <label for="nomor_lisensi">Nomor Lisensi</label>
                <input type="text" id="nomor_lisensi" name="nomor_lisensi" value="{{ old('nomor_lisensi', $license->nomor_lisensi ?? '') }}" maxlength="150">
            </div>
            <div class="app-form-group">
                <label for="jenis_lisensi">Jenis Lisensi</label>
                <input type="text" id="jenis_lisensi" name="jenis_lisensi" value="{{ old('jenis_lisensi', $license->jenis_lisensi ?? '') }}" maxlength="100">
            </div>
            <div class="app-form-group">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $license->tanggal_mulai ?? '') }}">
            </div>
            <div class="app-form-group">
                <label for="tanggal_kadaluarsa">Tanggal Berakhir</label>
                <input type="date" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa', $license->tanggal_kadaluarsa ?? '') }}">
            </div>
            <div class="app-form-group lic-form-logo-group">
                <label for="logo">Foto / Logo Lisensi</label>
                <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp">
                <small>JPG, PNG, atau WEBP, maksimal 5 MB.</small>
                @if($license && $license->logo)
                    <img class="lic-form-logo-preview" src="{{ asset('images/' . $license->logo) }}" alt="Logo {{ $license->nama_lisensi }}">
                @endif
            </div>
        </div>

        <h3 class="app-form-section">Billing Information</h3>
        <div class="app-form-grid">
            <div class="app-form-group">
                <label for="billing_status">Status Pembayaran</label>
                <select id="billing_status" name="billing_status">
                    <option value="">-- Pilih Status --</option>
                    @foreach(['Paid', 'Unpaid'] as $status)
                        <option value="{{ $status }}" {{ old('billing_status', $billing->status_pembayaran ?? '') === $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="app-form-group">
                <label for="billing_period">Billing Period</label>
                <input type="text" id="billing_period" name="billing_period" value="{{ old('billing_period', $billing->siklus_tagihan ?? '') }}" placeholder="Contoh: 1 Tahun">
            </div>
            <div class="app-form-group">
                <label for="last_payment">Last Payment</label>
                <input type="date" id="last_payment" name="last_payment" value="{{ old('last_payment', $billing->tanggal_bayar_terakhir ?? '') }}">
            </div>
            <div class="app-form-group">
                <label for="next_due_date">Next Due Date</label>
                <input type="date" id="next_due_date" name="next_due_date" value="{{ old('next_due_date', $billing->tanggal_jatuh_tempo ?? '') }}">
            </div>
            <div class="app-form-group">
                <label for="amount">Amount (Rp)</label>
                <input type="number" id="amount" name="amount" value="{{ old('amount', $billing->jumlah ?? '') }}" min="0" step="0.01">
            </div>
            <div class="app-form-group">
                <label for="payment_method">Payment Method</label>
                <input type="text" id="payment_method" name="payment_method" value="{{ old('payment_method', $billing->metode_pembayaran ?? '') }}">
            </div>
        </div>

        <h3 class="app-form-section">Asset Information</h3>
        <div class="app-form-grid">
            <div class="app-form-group">
                <label for="id_aset">Aset Terkait</label>
                <select id="id_aset" name="id_aset">
                    <option value="">-- Tidak ditautkan --</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id_aset }}" {{ (string) old('id_aset', $selectedAssetId) === (string) $asset->id_aset ? 'selected' : '' }}>
                            {{ $asset->nama_aset }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="app-form-actions">
            <a href="{{ route('licenses.licenses') }}" class="app-btn-cancel">Batal</a>
            <button type="submit" class="app-btn-submit">{{ $license ? 'Simpan Perubahan' : 'Simpan Lisensi' }}</button>
        </div>
    </form>
</div>
@endsection